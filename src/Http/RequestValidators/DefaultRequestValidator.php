<?php
declare(strict_types=1);

namespace App\Http\RequestValidators;

class DefaultRequestValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    protected function getValidationRules(array $requestParams): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedBodyParameters(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedQueryStringParameters(): array
    {
        return [];
    }
}
