<?php
declare(strict_types=1);

namespace App\Http\Traits;

use App\Routing\Request;
use Doctrine\ORM\EntityRepository;
use Exception;

trait FetchAllEntitiesTrait
{
    /**
     * @return Request
     */
    abstract protected function getRequest(): Request;

    /**
     * @return EntityRepository
     */
    abstract protected function getEntityRepository(): EntityRepository;

    /**
     * @return array
     * @throws Exception
     */
    public function fetchAll()
    {
        $queryParameters = $this->getRequest()->getQueryStringParameters();

        return $this->getEntityRepository()->findBy($queryParameters);
    }
}
