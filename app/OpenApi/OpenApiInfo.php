<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'API RESTful Laravel',
    version: '1.0.0',
    description: 'Swagger documentation for the RESTful API with session-based authentication.',
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local development server'
)]
#[OA\SecurityScheme(
    securityScheme: 'session',
    type: 'apiKey',
    in: 'cookie',
    name: 'laravel_session',
    description: 'Session cookie authentication for Laravel.'
)]
class OpenApiInfo {}
