<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Builders\SubscriberBuilder;
use App\Entities\Subscriber;
use App\Http\Traits\DeleteEntityTrait;
use App\Http\Traits\FetchAllEntitiesTrait;
use App\Http\Traits\FetchEntityTrait;
use App\Http\Traits\UpdateEntityTrait;
use App\Repositories\SubscriberRepository;
use App\Routing\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use pmill\Doctrine\Hydrator\ArrayHydrator;

class SubscriberController
{
    use DeleteEntityTrait;
    use FetchEntityTrait;
    use FetchAllEntitiesTrait;
    use UpdateEntityTrait;

    /**
     * @var ArrayHydrator
     */
    protected $arrayHydrator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SubscriberRepository
     */
    protected $subscriberRepository;

    /**
     * @var SubscriberBuilder
     */
    protected $subscriberBuilder;

    /**
     * SubscriberController constructor.
     *
     * @param ArrayHydrator $arrayHydrator
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param SubscriberRepository $subscriberRepository
     * @param SubscriberBuilder $subscriberBuilder
     */
    public function __construct(
        ArrayHydrator $arrayHydrator,
        EntityManagerInterface $entityManager,
        Request $request,
        SubscriberRepository $subscriberRepository,
        SubscriberBuilder $subscriberBuilder
    ) {
        $this->arrayHydrator = $arrayHydrator;
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->subscriberRepository = $subscriberRepository;
        $this->subscriberBuilder = $subscriberBuilder;
    }

    /**
     * @return Subscriber
     * @throws Exception
     */
    public function store()
    {
        $requestParameters = $this->request->getJsonRequestParameters();

        $subscriber = $this->subscriberBuilder->buildSubscriber($requestParameters);

        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();

        return $subscriber;
    }

    /**
     * @inheritDoc
     */
    protected function getArrayHydrator(): ArrayHydrator
    {
        return $this->arrayHydrator;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityRepository(): EntityRepository
    {
        return $this->subscriberRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityClass(): string
    {
        return Subscriber::class;
    }

    /**
     * @inheritDoc
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
