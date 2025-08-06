<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Dto\ProjectDtoAssembler;
use App\Dto\SettingDtoAssembler;
use App\Entity\Project;
use App\Entity\ProjectMethod;
use App\Repository\ProjectMethodRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectSettingRepository;
use App\Service\ProjectMethodService;
use App\Service\ProjectService;
use App\Service\ProjectSettingService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Tag;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;
use Yiisoft\RequestProvider\RequestProviderInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

#[Tag(
    name: 'project',
    description: 'Project service API'
)]
#[SecurityScheme(
    securityScheme: 'SessionCookie',
    type: 'apiKey',
    name: 'ory_kratos_session',
    in: 'cookie'
)]
final readonly class ProjectController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectMethodRepository $projectMethodRepository,
        private ProjectSettingRepository $projectSettingRepository,
        private ProjectDtoAssembler $projectDtoAssembler,
        private SettingDtoAssembler $settingDtoAssembler,
        private ProjectService $projectService,
        private ProjectMethodService $projectMethodService,
        private ProjectSettingService $projectSettingService,
    ) {}

    #[OA\Get(
        path: '/project',
        security: [['SessionCookie' => []]],
        tags: ['project'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Return projects',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                        new Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/Project'),
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'include',
        name: 'include',
        description: 'Includes list',
        in: 'query',
        required: true,
        schema: new Schema(type: 'string'),
        example: 'method,setting',
    )]
    public function getProjects(
        RequestProviderInterface $requestProvider,
        DataResponseFactoryInterface $responseFactory
    ): ResponseInterface {
        $userId = $requestProvider->get()->getAttribute('session')->getIdentity()->getId();

        // TODO Use separate table for project <-> user
        $projectsByUser = $this->projectRepository->findByUserId($userId);
        $projectIds = array_map(fn (Project $project) => $project->getId(), $projectsByUser);

        $projectsDto = [];

        foreach ($projectIds as $projectId) {
            $projectsDto[] = $this->projectDtoAssembler->assemble(
                $this->projectRepository->getById($projectId),
                explode(',', $requestProvider->get()->getQueryParams()['include'] ?? '')
            );
        }

        return $responseFactory->createResponse($projectsDto);
    }

    #[OA\Post(
        path: '/project',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userId'],
                properties: [
                    new OA\Property(property: 'userId', type: 'string', example: "452ccdc5-60a9-42f5-a1dd-4a2e75c2ce63")
                ],
                type: 'object'
            )
        ),
        tags: ['project'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Return created projects',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                        new Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/Project')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function createProjects(RequestProviderInterface $requestProvider, DataResponseFactoryInterface $responseFactory): ResponseInterface
    {
        $payload = $requestProvider->get()->getParsedBody();
        $projects = [
            $this->projectService->createWithDefaultMethods($payload['userId'], false),
            $this->projectService->createWithDefaultMethods($payload['userId'], true),
        ];

        return $responseFactory->createResponse(array_map([$this->projectDtoAssembler, 'assemble'], $projects), Status::CREATED);
    }

    #[OA\Get(
        path: '/project/{projectId}/method',
        tags: ['project'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                        new Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/ProjectMethod'),
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'projectId',
        name: 'projectId',
        description: 'Project Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function getMethods(
        DataResponseFactoryInterface $responseFactory,
        #[RouteArgument('projectId')]
        int $projectId,
    ): ResponseInterface {
        $projectMethods = $this->projectMethodRepository->findByProjectId($projectId);

        return $responseFactory->createResponse(array_map(fn (ProjectMethod $projectMethod) => $projectMethod->getMethodId(), $projectMethods));
    }

    #[OA\Put(
        path: '/project/{projectId}/method',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(type: 'integer'),
                example: [1, 2, 3]
            )
        ),
        tags: ['project'],
        responses: [
            new OA\Response(
                response:'200',
                description:'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                        new Schema(properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: '#/components/schemas/ProjectMethod'),
                            ),
                        ]),
                    ]
                ),
            ),
        ]
    )]
    #[Parameter(
        parameter: 'projectId',
        name: 'projectId',
        description: 'Project Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function saveMethods(
        RequestProviderInterface $requestProvider,
        DataResponseFactoryInterface $responseFactory,
        #[RouteArgument('projectId')]
        int $projectId,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $this->projectMethodService->replaceMethods($projectId, $payload);

        return $responseFactory->createResponse($payload);
    }

    #[OA\Get(
        path: '/project/{projectId}/setting',
        tags: ['project'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                        new Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/Setting')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'projectId',
        name: 'projectId',
        description: 'Project Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function getSettings(
        DataResponseFactoryInterface $responseFactory,
        #[RouteArgument('projectId')]
        int $projectId,
    ): ResponseInterface {
        $projectSettings = $this->projectSettingRepository->findByProjectId($projectId);

        return $responseFactory->createResponse(array_map([$this->settingDtoAssembler, 'assemble'], $projectSettings));
    }

    #[OA\Patch(
        path: '/project/{projectId}/setting',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/Setting')
            )
        ),
        tags: ['project'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Settings updated successfully',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'projectId',
        name: 'projectId',
        description: 'Project Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function updateSettings(
        RequestProviderInterface $requestProvider,
        DataResponseFactoryInterface $responseFactory,
        #[RouteArgument('projectId')]
        int $projectId,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $this->projectSettingService->updateSettings($projectId, $payload);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }
}
