<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Interfaces\PresentableInterface;
use Exception;

class HttpException extends Exception implements PresentableInterface
{
    /**
     * @var int
     */
    protected $httpStatusCode;

    /**
     * HttpException constructor.
     *
     * @param int $httpStatusCode
     * @param string $errorMessage
     */
    public function __construct($httpStatusCode, $errorMessage)
    {
        parent::__construct($errorMessage);

        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'message' => $this->getMessage(),
        ];
    }
}
