<?php
namespace App\Http\Traits;

use Doctrine\ORM\EntityRepository;
use Exception;

trait FetchEntityTrait
{
    /**
     * @return EntityRepository
     */
    abstract protected function getEntityRepository(): EntityRepository;

    /**
     * @param int|string $id
     *
     * @return object
     * @throws Exception
     */
    public function fetch($id)
    {
        return $this->getEntityRepository()->find($id);
    }
}
