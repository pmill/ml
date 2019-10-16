<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Routing\ArrayResponse;

class CorsController
{
    public function cors()
    {
        return new ArrayResponse([]);
    }
}
