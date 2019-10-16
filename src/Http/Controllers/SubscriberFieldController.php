<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entities\SubscriberField;
use App\Http\Traits\FetchAllEntitiesTrait;
use App\Http\Traits\UpdateEntityTrait;
use App\Repositories\SubscriberFieldRepository;
use App\Routing\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use pmill\Doctrine\Hydrator\ArrayHydrator;

class SubscriberFieldController
{
    use FetchAllEntitiesTrait, UpdateEntityTrait;

    /**
     * @var ArrayHydrator
     */
    protected $arrayHydrator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SubscriberFieldRepository
     */
    protected $subscriberFieldRepository;

    /**
     * @var Request
     */
    protected $request;

    /**
     * SubscriberFieldController constructor.
     *
     * @param ArrayHydrator $arrayHydrator
     * @param EntityManagerInterface $entityManager
     * @param SubscriberFieldRepository $subscriberFieldRepository
     * @param Request $request
     */
    public function __construct(
        ArrayHydrator $arrayHydrator,
        EntityManagerInterface $entityManager,
        SubscriberFieldRepository $subscriberFieldRepository,
        Request $request
    ) {
        $this->arrayHydrator = $arrayHydrator;
        $this->entityManager = $entityManager;
        $this->subscriberFieldRepository = $subscriberFieldRepository;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityRepository(): EntityRepository
    {
        return $this->subscriberFieldRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityClass(): string
    {
        return SubscriberField::class;
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
