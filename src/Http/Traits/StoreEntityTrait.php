<?php
declare(strict_types=1);

namespace App\Http\Traits;

use App\Routing\Request;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use pmill\Doctrine\Hydrator\ArrayHydrator;

trait StoreEntityTrait
{
    /**
     * @return string
     */
    abstract protected function getEntityClass(): string;

    /**
     * @return Request
     */
    abstract protected function getRequest(): Request;

    /**
     * @return ArrayHydrator
     */
    abstract protected function getArrayHydrator(): ArrayHydrator;

    /**
     * @return EntityManagerInterface
     */
    abstract protected function getEntityManager(): EntityManagerInterface;

    /**
     * @return object
     * @throws Exception
     */
    public function store()
    {
        $requestParameters = $this->getRequest()->getJsonRequestParameters();

        $entity = $this->getArrayHydrator()->hydrate($this->getEntityClass(), $requestParameters);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        return $entity;
    }
}
