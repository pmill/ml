<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Routing\ArrayResponse;

class CorsController
{
    /**
     * @return ArrayResponse
     */
    public function cors()
    {
        return new ArrayResponse([]);
    }
}
