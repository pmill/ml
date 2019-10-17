<?php
declare(strict_types=1);

namespace App\Http\RequestValidators;

class FetchAllSubscribersValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    protected function getValidationRules(array $requestParams): array
    {
        return [
            'querystring.subscriberGroup' => 'uuidV4',
        ];
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
        return [
            'subscriberGroup'
        ];
    }
}
