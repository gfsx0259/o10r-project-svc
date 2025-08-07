<?php

declare(strict_types=1);

namespace App\Controller\Gateway;

use App\Dto\Gateway\CallbackDto;
use App\Dto\Gateway\RouteDto;
use App\Dto\Gateway\ScenarioDto;
use App\Entity\Gateway\Callback;
use App\Entity\Gateway\Route;
use App\Entity\Gateway\Scenario;
use App\Repository\CallbackRepository;
use App\Repository\RouteRepository;
use App\Repository\ScenarioRepository;
use App\Service\GatewayCallbackService;
use App\Service\GatewayRouteService;
use App\Service\GatewayScenarioService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Tag;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;
use Yiisoft\RequestProvider\RequestProviderInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

#[Tag(
    name: 'gateway/manage',
    description: 'Manage gateway API'
)]
readonly class ManageController
{
    public function __construct(
        private RouteRepository $routeRepository,
        private ScenarioRepository $scenarioRepository,
        private CallbackRepository $callbackRepository,
    ) {}

    #[OA\Get(
        path: '/gateway/route',
        description: '',
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response:'200',
                description:'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: '#/components/schemas/Route'),
                            ),
                        ]),
                    ]
                ),
            ),
        ]
    )]
    public function getRoutes(DataResponseFactoryInterface $responseFactory): ResponseInterface
    {
        $routes = $this->routeRepository->findAll();

        $routesDto = array_map(
            fn (Route $route) => new RouteDto($route->getId(), $route->getMethodId(), $route->getConditions()),
            $routes,
        );

        return $responseFactory->createResponse($routesDto);
    }

    #[OA\Post(
        path: '/gateway/route',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/RouteCreate',
                type: 'object',
            )
        ),
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
                content: new JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    ref: '#/components/schemas/Route',
                                    type: 'object'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function createRoute(
        DataResponseFactoryInterface $responseFactory,
        GatewayRouteService $gatewayRouteService,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $route = $gatewayRouteService->persist($payload);

        return $responseFactory->createResponse([
            'id' => $route->getId(),
            'method_id' => $route->getMethodId(),
            'conditions' => $route->getConditions(),
        ], Status::CREATED);
    }

    #[OA\Patch(
        path: '/gateway/route/{routeId}',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/RouteCreate',
                type: 'object',
            )
        ),
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
                content: new JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'routeId',
        name: 'routeId',
        description: 'Route Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function updateRoute(
        DataResponseFactoryInterface $responseFactory,
        GatewayRouteService $gatewayRouteService,
        RequestProviderInterface $requestProvider,
        #[RouteArgument('routeId')]
        int $routeId
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $gatewayRouteService->persist(array_merge($payload, ['id' => $routeId]));

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Delete(
        path: '/gateway/route/{routeId}',
        description: '',
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
            )
        ]
    )]
    #[Parameter(
        parameter: 'routeId',
        name: 'routeId',
        description: 'Route Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function deleteRoute(
        DataResponseFactoryInterface $responseFactory,
        GatewayRouteService $gatewayRouteService,
        #[RouteArgument('routeId')]
        int $routeId
    ): ResponseInterface {
        $gatewayRouteService->delete($routeId);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Get(
        path: '/gateway/scenario',
        description: '',
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response:'200',
                description:'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: '#/components/schemas/Route'),
                            ),
                        ]),
                    ]
                ),
            ),
        ]
    )]
    public function getScenario(DataResponseFactoryInterface $responseFactory): ResponseInterface
    {
        $scenarios = $this->scenarioRepository->findAll();

        $scenariosDto = array_map(
            fn (Scenario $scenario) => new ScenarioDto(
                $scenario->getId(),
                $scenario->getRouteId(),
                $scenario->getTitle(),
                $scenario->getConditions(),
            ),
            $scenarios,
        );

        return $responseFactory->createResponse($scenariosDto);
    }

    #[OA\Post(
        path: '/gateway/scenario',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/ScenarioCreate',
                type: 'object',
            )
        ),
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
                content: new JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    ref: '#/components/schemas/Scenario',
                                    type: 'object'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function createScenario(
        DataResponseFactoryInterface $responseFactory,
        GatewayScenarioService $gatewayScenarioService,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $scenario = $gatewayScenarioService->persist($payload);

        return $responseFactory->createResponse([
            'id' => $scenario->getId(),
            'title' => $scenario->getTitle(),
            'route_id' => $scenario->getRouteId(),
            'conditions' => $scenario->getConditions(),
        ], Status::CREATED);
    }

    #[OA\Patch(
        path: '/gateway/scenario/{scenarioId}',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/ScenarioCreate',
                type: 'object',
            )
        ),
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
                content: new JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'scenarioId',
        name: 'scenarioId',
        description: 'Scenario Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function updateScenario(
        DataResponseFactoryInterface $responseFactory,
        GatewayScenarioService $gatewayScenarioService,
        RequestProviderInterface $requestProvider,
        #[RouteArgument('scenarioId')]
        int $scenarioId
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $gatewayScenarioService->persist(array_merge($payload, ['id' => $scenarioId]));

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Delete(
        path: '/gateway/scenario/{scenarioId}',
        description: '',
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
            )
        ]
    )]
    #[Parameter(
        parameter: 'scenarioId',
        name: 'scenarioId',
        description: 'Scenario Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function deleteScenario(
        DataResponseFactoryInterface $responseFactory,
        GatewayScenarioService $gatewayScenarioService,
        #[RouteArgument('scenarioId')]
        int $scenarioId
    ): ResponseInterface {
        $gatewayScenarioService->delete($scenarioId);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }


    #[OA\Get(
        path: '/gateway/callback',
        description: '',
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response:'200',
                description:'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: '#/components/schemas/Callback'),
                            ),
                        ]),
                    ]
                ),
            ),
        ]
    )]
    public function getCallback(DataResponseFactoryInterface $responseFactory): ResponseInterface
    {
        $callbacks = $this->callbackRepository->findAll();

        $callbacksDto = array_map(
            fn (Callback $callback) => new CallbackDto(
                $callback->getId(),
                $callback->getScenarioId(),
                $callback->getBody(),
                $callback->getOrder(),
            ),
            $callbacks,
        );

        return $responseFactory->createResponse($callbacksDto);
    }

    #[OA\Post(
        path: '/gateway/callback',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/CallbackCreate',
                type: 'object',
            )
        ),
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
                content: new JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    ref: '#/components/schemas/Callback',
                                    type: 'object'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function createCallback(
        DataResponseFactoryInterface $responseFactory,
        GatewayCallbackService $gatewayCallbackService,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $callback = $gatewayCallbackService->persist($payload);

        return $responseFactory->createResponse([
            'id' => $callback->getId(),
            'scenario_id' => $callback->getScenarioId(),
            'body' => $callback->getBody(),
            'order' => $callback->getOrder(),
        ], Status::CREATED);
    }

    #[OA\Patch(
        path: '/gateway/callback/{callbackId}',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/CallbackCreate',
                type: 'object',
            )
        ),
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
                content: new JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'callbackId',
        name: 'callbackId',
        description: 'Callback Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function updateCallback(
        DataResponseFactoryInterface $responseFactory,
        GatewayCallbackService $gatewayCallbackService,
        RequestProviderInterface $requestProvider,
        #[RouteArgument('callbackId')]
        int $callbackId
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $gatewayCallbackService->persist(array_merge($payload, ['id' => $callbackId]));

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Delete(
        path: '/gateway/callback/{callbackId}',
        description: '',
        tags: ['gateway/manage'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Created',
            )
        ]
    )]
    #[Parameter(
        parameter: 'callbackId',
        name: 'callbackId',
        description: 'Callback Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function deleteCallback(
        DataResponseFactoryInterface $responseFactory,
        GatewayCallbackService $gatewayCallbackService,
        #[RouteArgument('callbackId')]
        int $callbackId
    ): ResponseInterface {
        $gatewayCallbackService->delete($callbackId);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }
}
