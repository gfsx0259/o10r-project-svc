<?php

declare(strict_types=1);

use App\Controller\AggregationController;
use App\Controller\IndexController;
use App\Controller\MethodController;
use App\Controller\ProjectController;
use App\Middleware\SessionMiddleware;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsHtml;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Swagger\Middleware\SwaggerJson;
use Yiisoft\Swagger\Middleware\SwaggerUi;
use Yiisoft\Yii\Middleware\CorsAllowAll;

return [
    Route::get('/')
        ->action([IndexController::class, 'index'])
        ->name('app/index'),

    Route::get('/project')
        ->middleware(SessionMiddleware::class)
        ->action([ProjectController::class, 'getProjects']),

    Route::post('/project')
        ->action([ProjectController::class, 'createProjects']),

    Route::get('/project/{projectId}/method')
        ->action([ProjectController::class, 'getMethods']),

    Route::put('/project/{projectId}/method')
        ->action([ProjectController::class, 'saveMethods']),

    Route::get('/project/{projectId}/setting')
        ->action([ProjectController::class, 'getSettings']),

    Route::patch('/project/{projectId}/setting')
        ->action([ProjectController::class, 'updateSettings']),

    Route::get('/aggregation/payment-page/{projectHash}')
        ->action([AggregationController::class, 'paymentPage']),

    Route::get('/method')
        ->action([MethodController::class, 'index']),

    Group::create('/docs')
        ->routes(
            Route::get('')
                ->middleware(FormatDataResponseAsHtml::class)
                ->action(fn (SwaggerUi $swaggerUi) => $swaggerUi->withJsonUrl('/docs/openapi.json')),
            Route::get('/openapi.json')
                ->middleware(FormatDataResponseAsJson::class)
                ->middleware(CorsAllowAll::class)
                ->action([SwaggerJson::class, 'process']),
        ),
];
