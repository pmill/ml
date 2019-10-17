<?php

use App\Http\Controllers\CorsController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriberFieldController;
use App\Http\Controllers\SubscriberGroupController;
use App\Http\RequestValidators\CreateSubscriberGroupValidator;
use App\Http\RequestValidators\CreateSubscriberValidator;
use App\Http\RequestValidators\FetchAllSubscriberFieldsValidator;
use App\Http\RequestValidators\FetchAllSubscribersValidator;
use App\Http\RequestValidators\UpdateSubscriberFieldValidator;
use App\Http\RequestValidators\UpdateSubscriberValidator;
use App\Routing\RouteDefinition;
use FastRoute\RouteCollector;

/** @var RouteCollector $routeCollector */

$routeCollector->addGroup('/api', function (RouteCollector $routeCollector) {
    // field collection routes
    $routeCollector->get(
        '/field',
        new RouteDefinition(
            FieldController::class,
            'fetchAll'
        )
    );

    // subscriber collection routes
    $routeCollector->addRoute(
        'OPTIONS',
        '/subscriber',
        new RouteDefinition(
            CorsController::class,
            'cors'
        )
    );

    $routeCollector->get(
        '/subscriber',
        new RouteDefinition(
            SubscriberController::class,
            'fetchAll',
            FetchAllSubscribersValidator::class
        )
    );

    $routeCollector->post(
        '/subscriber',
        new RouteDefinition(
            SubscriberController::class,
            'store',
            CreateSubscriberValidator::class
        )
    );

    // subscriber item routes
    $routeCollector->addRoute(
        'OPTIONS',
        '/subscriber/{id}',
        new RouteDefinition(
            CorsController::class,
            'cors'
        )
    );

    $routeCollector->get(
        '/subscriber/{id}',
        new RouteDefinition(
            SubscriberController::class,
            'fetch'
        )
    );

    $routeCollector->patch(
        '/subscriber/{id}',
        new RouteDefinition(
            SubscriberController::class,
            'update',
            UpdateSubscriberValidator::class
        )
    );

    $routeCollector->delete(
        '/subscriber/{id}',
        new RouteDefinition(
            SubscriberController::class,
            'delete'
        )
    );

    // subscriber field collection routes
    $routeCollector->get(
        '/subscriber-field',
        new RouteDefinition(
            SubscriberFieldController::class,
            'fetchAll',
            FetchAllSubscriberFieldsValidator::class
        )
    );

    // subscriber field item routes
    $routeCollector->addRoute(
        'OPTIONS',
        '/subscriber-field/{id}',
        new RouteDefinition(
            CorsController::class,
            'cors'
        )
    );

    $routeCollector->patch(
        '/subscriber-field/{id}',
        new RouteDefinition(
            SubscriberFieldController::class,
            'update',
            UpdateSubscriberFieldValidator::class
        )
    );

    // subscriber group collection routes
    $routeCollector->addRoute(
        'OPTIONS',
        '/subscriber-group',
        new RouteDefinition(
            CorsController::class,
            'cors'
        )
    );

    $routeCollector->get(
        '/subscriber-group',
        new RouteDefinition(
            SubscriberGroupController::class,
            'fetchAll'
        )
    );

    $routeCollector->post(
        '/subscriber-group',
        new RouteDefinition(
            SubscriberGroupController::class,
            'store',
            CreateSubscriberGroupValidator::class
        )
    );

    // subscriber group item routes
    $routeCollector->get(
        '/subscriber-group/{id}',
        new RouteDefinition(
            SubscriberGroupController::class,
            'fetch'
        )
    );
});
