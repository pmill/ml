<?php
declare(strict_types=1);

namespace App;

use App\Entities\Field;
use App\Entities\FieldType;
use App\Entities\Subscriber;
use App\Entities\SubscriberField;
use App\Entities\SubscriberGroup;
use App\Entities\SubscriberState;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\HttpException;
use App\Http\Presenters\JsonPresenter;
use App\Interfaces\PresenterInterface;
use App\Interfaces\ValidatorInterface;
use App\Repositories\FieldRepository;
use App\Repositories\FieldTypeRepository;
use App\Repositories\SubscriberFieldRepository;
use App\Repositories\SubscriberGroupRepository;
use App\Repositories\SubscriberRepository;
use App\Repositories\SubscriberStateRepository;
use App\Routing\ArrayResponse;
use App\Routing\Request;
use App\Routing\RouteDispatcher;
use App\Routing\Router;
use App\ValidationRules\EmailHostValidationRule;
use App\ValidationRules\UuidV4ValidationRule;
use Cache\Adapter\Predis\PredisCachePool;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use Exception;
use Predis\Client;
use Psr\SimpleCache\CacheInterface;
use Rakit\Validation\RuleQuashException;
use Rakit\Validation\Validator;
use Ramsey\Uuid\Doctrine\UuidType;
use Symfony\Component\HttpFoundation\Response;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

use function DI\factory as diFactory;

class Application
{
    /**
     * @var Container
     */
    protected $serviceContainer;

    /**
     * Application constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $whoops = new WhoopsRun();
        $whoops->prependHandler(new PrettyPageHandler());
        $whoops->register();

        $dotenv = Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        $this->setupDependencyInjection();
    }

    /**
     * Sets up dependencies for anything that can't be setup with autowiring
     *
     * @throws Exception
     */
    protected function setupDependencyInjection()
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions([
            Request::class => function () {
                return Request::createFromGlobals();
            },
            RouteDispatcher::class => function (Container $serviceContainer) {
                return new RouteDispatcher($serviceContainer);
            },
            Router::class => diFactory([$this, 'bootstrapRouter']),
            CacheInterface::class => diFactory([$this, 'bootstrapCache']),
            EntityManagerInterface::class => diFactory([$this, 'bootstrapDoctrine']),
            Validator::class => diFactory([$this, 'bootstrapValidator']),
        ]);

        $this->setupRepositoryDependencyInjection($containerBuilder);

        $this->serviceContainer = $containerBuilder->build();
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    protected function setupRepositoryDependencyInjection(ContainerBuilder $containerBuilder)
    {
        $repositoryEntityMap = [
            FieldRepository::class => Field::class,
            FieldTypeRepository::class => FieldType::class,
            SubscriberFieldRepository::class => SubscriberField::class,
            SubscriberGroupRepository::class => SubscriberGroup::class,
            SubscriberRepository::class => Subscriber::class,
            SubscriberStateRepository::class => SubscriberState::class,
        ];

        foreach ($repositoryEntityMap as $repositoryClassName => $entityClassName) {
            $containerBuilder->addDefinitions([
                $repositoryClassName => function (Container $serviceContainer) use ($entityClassName) {
                    /** @var EntityManagerInterface $entityManager */
                    $entityManager = $serviceContainer->get(EntityManagerInterface::class);

                    return $entityManager->getRepository($entityClassName);
                },
            ]);
        }
    }

    /**
     * @return CacheInterface
     */
    public function bootstrapCache()
    {
        $client = new Client(
            sprintf(
                'tcp://%s:%d',
                getenv('REDIS_HOST'),
                intval(getenv('REDIS_PORT'))
            )
        );

        return new PredisCachePool($client);
    }

    /**
     * @return Router
     */
    public function bootstrapRouter()
    {
        $router = new Router();

        $router->initialiseFromRouteFiles([
            __DIR__ . '/../routes/api.php',
        ]);

        return $router;
    }

    /**
     * @return EmailHostValidationRule|mixed
     * @throws DependencyException
     * @throws NotFoundException
     * @throws RuleQuashException
     */
    public function bootstrapValidator()
    {
        $emailHostValidationRule = $this->serviceContainer->get(EmailHostValidationRule::class);
        $uuidV4ValidationRule = $this->serviceContainer->get(UuidV4ValidationRule::class);

        $validator = new Validator();
        $validator->addValidator('emailHost', $emailHostValidationRule);
        $validator->addValidator('uuidV4', $uuidV4ValidationRule);

        return $validator;
    }

    /**
     * @return EntityManager
     * @throws ORMException
     * @throws DBALException
     */
    public function bootstrapDoctrine()
    {
        $chainCache = new ChainCache([
            new ArrayCache(),
            new ApcuCache(),
        ]);

        $config = Setup::createAnnotationMetadataConfiguration(
            [
                __DIR__ . "/Entities",
            ],
            getenv('DOCTRINE_DEV_MODE'),
            __DIR__ . "/../" . getenv('DOCTRINE_PROXY_DIR'),
            $chainCache,
            false
        );

        $conn = [
            'dbname' => getenv('DB_NAME'),
            'user' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'host' => getenv('DB_HOST'),
            'driver' => getenv('DB_DRIVER'),
            'port' => getenv('DB_PORT'),
        ];

        Type::addType('uuid', UuidType::class);

        return EntityManager::create($conn, $config);
    }

    /**
     * @return Container
     */
    public function getServiceContainer(): Container
    {
        return $this->serviceContainer;
    }

    /**
     * Runs the application:
     *
     * 1. Resolve the route from the request
     * 2. Runs the attached request validator (if present)
     * 3. Dispatch the route and get the result from the controller
     * 4. Pass the result to the presenter to send a response to the browser
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function run()
    {
        /** @var PresenterInterface $presenter */
        $presenter = $this->serviceContainer->get(JsonPresenter::class);

        try {
            /** @var Router $router */
            $router = $this->serviceContainer->get(Router::class);
            /** @var Request $request */
            $request = $this->serviceContainer->get(Request::class);

            $resolvedRoute = $router->findRoute($request);

            if ($requestValidatorClass = $resolvedRoute->getRouteDefinition()->getRequestValidatorClass()) {
                /** @var ValidatorInterface $requestValidator */
                $requestValidator = $this->serviceContainer->get($requestValidatorClass);

                $requestValidator->assertValid($request, $resolvedRoute->getRouteParameters());
            }

            /** @var RouteDispatcher $routeDispatcher */
            $routeDispatcher = $this->serviceContainer->get(RouteDispatcher::class);

            $routeResult = $routeDispatcher->dispatchRoute($resolvedRoute);

            $presenter->present($routeResult);
        } catch (HttpException $e) {
            $presenter->present($e, $e->getHttpStatusCode());
        } catch (EntityNotFoundException $e) {
            $presenter->present(
                new ArrayResponse(
                    [
                        'error' => "File not found",
                    ]
                ),
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            $presenter->present(
                new ArrayResponse(
                    [
                        'error' => "An error has occurred",
                        'exception' => (string)$e,
                    ]
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
