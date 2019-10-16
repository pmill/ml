<?php
namespace App\Http\Traits;

use App\Exceptions\EntityNotFoundException;
use App\Routing\Request;
use Doctrine\ORM\EntityManagerInterface;
use pmill\Doctrine\Hydrator\ArrayHydrator;

trait UpdateEntityTrait
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
     * @param string|int $id
     *
     * @return object
     * @throws EntityNotFoundException
     */
    public function update($id)
    {
        $requestParameters = $this->getRequest()->getJsonRequestParameters();

        $entity = $this->getEntityManager()->find($this->getEntityClass(), $id);
        if ($entity === null) {
            throw new EntityNotFoundException();
        }

        $entity = $this->getArrayHydrator()->hydrate($entity, $requestParameters);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        return $entity;
    }
}
