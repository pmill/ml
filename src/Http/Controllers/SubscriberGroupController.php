<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\SubscriberGroup;
use App\Http\Traits\FetchAllEntitiesTrait;
use App\Http\Traits\FetchEntityTrait;
use App\Http\Traits\StoreEntityTrait;
use App\Repositories\SubscriberGroupRepository;
use App\Routing\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use pmill\Doctrine\Hydrator\ArrayHydrator;

class SubscriberGroupController
{
    use FetchEntityTrait, FetchAllEntitiesTrait, StoreEntityTrait;

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
     * @var SubscriberGroupRepository
     */
    protected $subscriberGroupRepository;

    /**
     * SubscriberController constructor.
     *
     * @param ArrayHydrator $arrayHydrator
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param SubscriberGroupRepository $subscriberGroupRepository
     */
    public function __construct(
        ArrayHydrator $arrayHydrator,
        EntityManagerInterface $entityManager,
        Request $request,
        SubscriberGroupRepository $subscriberGroupRepository
    ) {
        $this->arrayHydrator = $arrayHydrator;
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->subscriberGroupRepository = $subscriberGroupRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityRepository(): EntityRepository
    {
        return $this->subscriberGroupRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityClass(): string
    {
        return SubscriberGroup::class;
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
    protected function getArrayHydrator(): ArrayHydrator
    {
        return $this->arrayHydrator;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
