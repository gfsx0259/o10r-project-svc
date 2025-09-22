<?php

declare(strict_types=1);

use App\Controller\Catalog\MethodController;
use App\Controller\Catalog\SchemaController;
use App\Controller\Gateway\DummyController;
use App\Controller\Gateway\ManageController;
use App\Controller\IndexController;
use App\Controller\Project\ProjectController;
use App\Middleware\AuthentificationMiddleware;
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

    Route::post('/gateway/access/{projectId}')
        ->middleware(AuthentificationMiddleware::class)
        ->action([DummyController::class, 'access']),

    Route::post('/gateway/payment')
        ->action([DummyController::class, 'payment']),

    Route::get('/gateway/payment/{paymentId}')
        ->action([DummyController::class, 'status']),

    Route::post('/gateway/complete')
        ->action([DummyController::class, 'complete'])
        ->name('redirectComplete'),

    Route::post('/gateway/clarification')
        ->action([DummyController::class, 'complete']),

    Route::post('/gateway/notification')
        ->action([DummyController::class, 'complete']),

    Route::get('/gateway/route')
        ->action([ManageController::class, 'getRoutes']),

    Route::post('/gateway/route')
        ->action([ManageController::class, 'createRoute']),

    Route::patch('/gateway/route/{routeId}')
        ->action([ManageController::class, 'updateRoute']),

    Route::delete('/gateway/route/{routeId}')
        ->action([ManageController::class, 'deleteRoute']),

    Route::get('/gateway/scenario')
        ->action([ManageController::class, 'getScenario']),

    Route::post('/gateway/scenario')
        ->action([ManageController::class, 'createScenario']),

    Route::post('/gateway/scenario/active')
        ->action([ManageController::class, 'setActiveScenario']),

    Route::get('/gateway/scenario/active')
        ->action([ManageController::class, 'getActiveScenario']),

    Route::patch('/gateway/scenario/{scenarioId}')
        ->action([ManageController::class, 'updateScenario']),

    Route::delete('/gateway/scenario/{scenarioId}')
        ->action([ManageController::class, 'deleteScenario']),

    Route::get('/gateway/callback')
        ->action([ManageController::class, 'getCallback']),

    Route::post('/gateway/callback')
        ->action([ManageController::class, 'createCallback']),

    Route::patch('/gateway/callback/{callbackId}')
        ->action([ManageController::class, 'updateCallback']),

    Route::delete('/gateway/callback/{callbackId}')
        ->action([ManageController::class, 'deleteCallback']),

    Route::get('/gateway/static')
        ->action([ManageController::class, 'getStaticRoutes']),

    Route::post('/gateway/static')
        ->action([ManageController::class, 'createStaticRoute']),

    Route::patch('/gateway/static/{staticId}')
        ->action([ManageController::class, 'updateStaticRoute']),

    Route::delete('/gateway/static/{staticId}')
        ->action([ManageController::class, 'deleteStaticRoute']),

    Route::get('/gateway{path:.+}')
        ->action([DummyController::class, 'staticRoute']),

    Route::post('/gateway{path:.+}')
        ->action([DummyController::class, 'staticRoute']),


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

    Route::post('/project/{projectId}/session')
        ->action([ProjectController::class, 'makeSession']),

    Route::get('/catalog/method')
        ->action([MethodController::class, 'read']),

    Route::post('/catalog/method')
        ->action([MethodController::class, 'create']),

    Route::patch('/catalog/method/{methodId}')
        ->action([MethodController::class, 'update']),

    Route::delete('/catalog/method/{methodId}')
        ->action([MethodController::class, 'delete']),

    Route::get('/catalog/schema')
        ->action([SchemaController::class, 'read']),

    Route::post('/catalog/schema')
        ->action([SchemaController::class, 'create']),

    Route::patch('/catalog/schema/{schemaId}')
        ->action([SchemaController::class, 'update']),

    Route::delete('/catalog/schema/{schemaId}')
        ->action([SchemaController::class, 'delete']),

    Route::get('/{page}')
        ->name('action/dummy'),

    Route::post('/proxy/{page}')
        ->action([DummyController::class, 'proxy'])
        ->name('proxy/dummy'),

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
