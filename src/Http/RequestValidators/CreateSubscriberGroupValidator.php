<?php
namespace App\Http\RequestValidators;

class CreateSubscriberGroupValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    protected function getValidationRules(array $requestParams): array
    {
        return [
            'body.name' => 'required|max:255',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedBodyParameters(): array
    {
        return [
            'name',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedQueryStringParameters(): array
    {
        return [];
    }
}
