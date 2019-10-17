<?php
declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpValidationException extends HttpException
{
    /**
     * @var array
     */
    protected $errorMessages;

    /**
     * HttpValidationException constructor.
     *
     * @param array $errorMessages
     */
    public function __construct(array $errorMessages)
    {
        parent::__construct(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'The given values are invalid for this request'
        );

        $this->errorMessages = $errorMessages;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errorMessages,
        ];
    }
}
