<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Traits\FetchAllEntitiesTrait;
use App\Repositories\FieldRepository;
use App\Routing\Request;
use Doctrine\ORM\EntityRepository;

class FieldController
{
    use FetchAllEntitiesTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FieldRepository
     */
    protected $fieldRepository;

    /**
     * FieldController constructor.
     *
     * @param Request $request
     * @param FieldRepository $fieldRepository
     */
    public function __construct(
        Request $request,
        FieldRepository $fieldRepository
    ) {
        $this->request = $request;
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityRepository(): EntityRepository
    {
        return $this->fieldRepository;
    }

    /**
     * @inheritDoc
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }
}
