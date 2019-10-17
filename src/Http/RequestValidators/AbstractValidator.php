<?php
declare(strict_types=1);

namespace App\Http\RequestValidators;

use App\Exceptions\HttpValidationException;
use App\Interfaces\ValidatorInterface;
use App\Routing\Request;
use Rakit\Validation\Validator;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * AbstractValidator constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $requestParams
     *
     * @return array
     */
    abstract protected function getValidationRules(array $requestParams): array;

    /**
     * @return string[]
     */
    abstract protected function getWhitelistedBodyParameters(): array;

    /**
     * @return string[]
     */
    abstract protected function getWhitelistedQueryStringParameters(): array;

    /**
     * @inheritDoc
     */
    public function assertValid(Request $request, array $requestParams)
    {
        $bodyParameters = $request->getJsonRequestParameters();
        $this->assertValidKeys($bodyParameters, $this->getWhitelistedBodyParameters());

        $queryStringParameters = $request->getQueryStringParameters();
        $this->assertValidKeys($queryStringParameters, $this->getWhitelistedQueryStringParameters());

        $parameters = [
            'body' => $bodyParameters,
            'querystring' => $queryStringParameters,
        ];

        $validationRules = $this->getValidationRules($requestParams);

        if (count($validationRules) === 0) {
            return;
        }

        $validation = $this->validator->make($parameters, $validationRules);
        $validation->validate();

        if ($validation->fails()) {
            throw new HttpValidationException($validation->errors->toArray());
        }
    }

    /**
     * @param array $parameters
     * @param string[] $whitelistedKeys
     *
     * @throws HttpValidationException
     */
    protected function assertValidKeys(array $parameters, array $whitelistedKeys)
    {
        $parameterKeys = array_keys($parameters);

        $unknownParameters = array_diff($parameterKeys, $whitelistedKeys);

        if (count($unknownParameters) > 0) {
            throw new HttpValidationException([
                'unknown-parameters' => $unknownParameters,
            ]);
        }
    }
}
