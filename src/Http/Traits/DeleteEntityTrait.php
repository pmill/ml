<?php
declare(strict_types=1);

namespace App\Http\Traits;

use App\Exceptions\EntityNotFoundException;
use App\Routing\ArrayResponse;
use Doctrine\ORM\EntityManagerInterface;

trait DeleteEntityTrait
{
    /**
     * @return string
     */
    abstract protected function getEntityClass(): string;

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
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();

        $entity = $entityManager->find($this->getEntityClass(), $id);
        if ($entity === null) {
            throw new EntityNotFoundException();
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        return new ArrayResponse([
            'success' => true,
        ]);
    }
}
