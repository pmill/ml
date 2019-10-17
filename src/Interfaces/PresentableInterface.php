<?php
declare(strict_types=1);

namespace App\Interfaces;

interface PresentableInterface
{
    /**
     * @return array
     */
    public function present(): array;
}
