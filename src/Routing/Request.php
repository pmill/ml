<?php
namespace App\Routing;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    /**
     * @return array
     */
    public function getJsonRequestParameters()
    {
        $requestContent = $this->getContent();

        $requestJsonContent = json_decode($requestContent, true);

        if ($requestJsonContent === null) {
            return [];
        }

        return $requestJsonContent;
    }

    /**
     * @return array
     */
    public function getQueryStringParameters()
    {
        return $this->query->all();
    }
}