<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\Exceptions\HttpValidationException;
use App\Routing\Request;

interface ValidatorInterface
{
    /**
     * @param Request $request
     * @param array $requestParams
     *
     * @throws HttpValidationException
     */
    public function assertValid(Request $request, array $requestParams);
}
