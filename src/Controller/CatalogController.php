<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Method;
use App\Entity\MethodFormSchema;
use App\Repository\MethodFormSchemaRepository;
use App\Repository\MethodRepository;
use App\Service\CatalogSchemeService;
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
    name: 'catalog',
    description: 'Catalog service API'
)]
final readonly class CatalogController
{
    public function __construct(
        private MethodRepository $methodRepository,
        private MethodFormSchemaRepository $methodFormSchemaRepository,
    ) {}

    #[OA\Get(
        path: '/catalog/method',
        description: '',
        tags: ['catalog'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'project_id', type: 'number', example: 1),
                                            new OA\Property(property: 'project_hash', type: 'string', example: 'ertgwered'),
                                        ],
                                        type: 'object',
                                    )
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getMethods(
        DataResponseFactoryInterface $responseFactory,
    ): ResponseInterface {
        $methods = $this->methodRepository->findAll();

        return $responseFactory->createResponse(array_map(fn (Method $method) => [
            'id' => $method->getId(),
            'code' => $method->getCode(),
            'title' => $method->getTitle(),
            'description' => $method->getDescription(),
        ], $methods));
    }

    #[OA\Get(
        path: '/catalog/schema',
        description: '',
        tags: ['catalog'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(ref: '#/components/schemas/MethodFormSchema'),
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getSchemas(
        DataResponseFactoryInterface $responseFactory,
    ): ResponseInterface {
        $schemas = $this->methodFormSchemaRepository->findAll();

        return $responseFactory->createResponse(array_map(fn (MethodFormSchema $schema) => [
            'id' => $schema->getId(),
            'method_id' => $schema->getMethodId(),
            'fields' => $schema->getFields(),
        ], $schemas));
    }

    #[OA\Post(
        path: '/catalog/schema',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/MethodFormSchemaCreate',
                type: 'object',
            )
        ),
        tags: ['catalog'],
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
                                    ref: '#/components/schemas/MethodFormSchema',
                                    type: 'object'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function createSchema(
        DataResponseFactoryInterface $responseFactory,
        CatalogSchemeService $catalogSchemeService,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $schema = $catalogSchemeService->create($payload);

        return $responseFactory->createResponse([
            'id' => $schema->getId(),
            'method_id' => $schema->getMethodId(),
            'fields' => $schema->getFields(),
        ], Status::CREATED);
    }

    #[OA\Patch(
        path: '/catalog/schema/{id}',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/MethodFormSchema',
                type: 'object',
            )
        ),
        tags: ['catalog'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Settings updated successfully',
                content: new JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                    ]
                )
            )
        ],
    )]
    #[Parameter(
        parameter: 'schemaId',
        name: 'schemaId',
        description: 'Schema Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function updateSchema(
        DataResponseFactoryInterface $responseFactory,
        CatalogSchemeService $catalogSchemeService,
        RequestProviderInterface $requestProvider,
        #[RouteArgument('schemaId')]
        int $schemaId
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $catalogSchemeService->update($schemaId, $payload);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Delete(
        path: '/catalog/schema/{id}',
        description: '',
        tags: ['catalog'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Settings removed successfully',
            )
        ],
    )]
    #[Parameter(
        parameter: 'schemaId',
        name: 'schemaId',
        description: 'Schema Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function deleteSchema(
        DataResponseFactoryInterface $responseFactory,
        CatalogSchemeService $catalogSchemeService,
        #[RouteArgument('schemaId')]
        int $schemaId
    ): ResponseInterface {

        $catalogSchemeService->delete($schemaId);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }
}
