<?php

declare(strict_types=1);

use App\Middleware\CorsMiddleware;
use Yiisoft\ErrorHandler\Middleware\ErrorCatcher;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Yii\Middleware\Subfolder;

return [
    'yiisoft/input-http' => [
        'requestInputParametersResolver' => [
            'throwInputValidationException' => true,
        ],
    ],

    'middlewares' => [
        CorsMiddleware::class,
        ErrorCatcher::class,
        Subfolder::class,
        Router::class,
    ],
];
