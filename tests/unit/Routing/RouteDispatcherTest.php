<?php
namespace Tests\Unit\Routing;

use App\Builders\SubscriberBuilder;
use App\Exceptions\DispatchingRouteException;
use App\Http\Controllers\SubscriberController;
use App\Repositories\SubscriberRepository;
use App\Routing\ResolvedRoute;
use App\Routing\RouteDefinition;
use App\Routing\RouteDispatcher;
use DI\Container;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use pmill\Doctrine\Hydrator\ArrayHydrator;
use Symfony\Component\HttpFoundation\Request;

class RouteDispatcherTest extends TestCase
{
    public function testUnknownController()
    {
        $this->expectException(DispatchingRouteException::class);

        $container = new Container();

        $resolvedRoute = new ResolvedRoute(
            new RouteDefinition(
                'UnknownController',
                'method',
                true
            ),
            []
        );

        $routeDispatcher = new RouteDispatcher($container);
        $routeDispatcher->dispatchRoute($resolvedRoute);
    }

    public function testUnknownControllerMethod()
    {
        $this->expectException(DispatchingRouteException::class);

        $mockArrayHydrator = Mockery::mock(ArrayHydrator::class);
        $mockEntityManager = Mockery::mock(EntityManagerInterface::class);
        $mockSubscriberBuilder = Mockery::mock(SubscriberBuilder::class);
        $mockSubscriberRepository = Mockery::mock(SubscriberRepository::class);

        $request = Request::create('http://localhost/api/subscriber', 'GET');

        $container = new Container();
        $container->set(ArrayHydrator::class, $mockArrayHydrator);
        $container->set(EntityManagerInterface::class, $mockEntityManager);
        $container->set(Request::class, $request);
        $container->set(SubscriberBuilder::class, $mockSubscriberBuilder);
        $container->set(SubscriberRepository::class, $mockSubscriberRepository);

        $resolvedRoute = new ResolvedRoute(
            new RouteDefinition(
                SubscriberController::class,
                'unknownMethod',
                true
            ),
            []
        );

        $routeDispatcher = new RouteDispatcher($container);
        $routeDispatcher->dispatchRoute($resolvedRoute);
    }

    public function testControllerCalled()
    {
        $mockArrayHydrator = Mockery::mock(ArrayHydrator::class);
        $mockEntityManager = Mockery::mock(EntityManagerInterface::class);
        $mockSubscriberBuilder = Mockery::mock(SubscriberBuilder::class);

        $mockSubscriberRepository = Mockery::mock(SubscriberRepository::class);
        $mockSubscriberRepository
            ->shouldReceive('findBy')
            ->andReturn([]);

        $request = Request::create('http://localhost/api/subscriber', 'GET');

        $container = new Container();
        $container->set(ArrayHydrator::class, $mockArrayHydrator);
        $container->set(EntityManagerInterface::class, $mockEntityManager);
        $container->set(Request::class, $request);
        $container->set(SubscriberBuilder::class, $mockSubscriberBuilder);
        $container->set(SubscriberRepository::class, $mockSubscriberRepository);

        $resolvedRoute = new ResolvedRoute(
            new RouteDefinition(
                SubscriberController::class,
                'fetchAll',
                true
            ),
            []
        );

        $routeDispatcher = new RouteDispatcher($container);
        $result = $routeDispatcher->dispatchRoute($resolvedRoute);

        $this->assertNotNull($result);
    }
}
