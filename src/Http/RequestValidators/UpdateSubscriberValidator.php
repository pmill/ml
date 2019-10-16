<?php
namespace App\Http\RequestValidators;

class UpdateSubscriberValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    protected function getValidationRules(array $requestParams): array
    {
        return [
            'body.subscriberGroup' => 'required|uuidV4',
            'body.subscriberState' => 'required|uuidV4',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhitelistedBodyParameters(): array
    {
        return [
            'subscriberGroup',
            'subscriberState',
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
