<?php
declare(strict_types=1);

namespace App\Routing;

use App\Http\RequestValidators\DefaultRequestValidator;

class RouteDefinition
{
    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $controllerMethod;

    /**
     * @var string
     */
    protected $requestValidatorClass;

    /**
     * RouteDefinition constructor.
     *
     * @param string $controllerClass
     * @param string $controllerMethod
     * @param string|null $requestValidatorClass
     */
    public function __construct(
        string $controllerClass,
        string $controllerMethod,
        string $requestValidatorClass = DefaultRequestValidator::class
    ) {
        $this->controllerClass = $controllerClass;
        $this->controllerMethod = $controllerMethod;
        $this->requestValidatorClass = $requestValidatorClass;
    }

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    /**
     * @return string
     */
    public function getRequestValidatorClass(): string
    {
        return $this->requestValidatorClass;
    }
}
