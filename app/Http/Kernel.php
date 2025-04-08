<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

final class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Web middleware group
            Middleware\ConvertResponseToCamelCase::class,
            Middleware\ConvertRequestToSnakeCase::class,
        ],

        'api' => [
            // ... other middleware
            Middleware\ConvertResponseToCamelCase::class,
            Middleware\ConvertRequestToSnakeCase::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Route middleware
    ];
}
