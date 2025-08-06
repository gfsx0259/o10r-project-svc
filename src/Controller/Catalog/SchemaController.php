<?php

declare(strict_types=1);

namespace App\Controller\Catalog;

use App\Entity\MethodFormSchema;
use App\Repository\MethodFormSchemaRepository;
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
    name: 'catalog/schema',
    description: 'Catalog service API'
)]
final readonly class SchemaController
{
    public function __construct(
        private MethodFormSchemaRepository $methodFormSchemaRepository,
    ) {}

    #[OA\Get(
        path: '/catalog/schema',
        description: '',
        tags: ['catalog/schema'],
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
    public function read(
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
        tags: ['catalog/schema'],
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
    public function create(
        DataResponseFactoryInterface $responseFactory,
        CatalogSchemeService $catalogSchemeService,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $schema = $catalogSchemeService->persist($payload);

        return $responseFactory->createResponse([
            'id' => $schema->getId(),
            'method_id' => $schema->getMethodId(),
            'fields' => $schema->getFields(),
        ], Status::CREATED);
    }

    #[OA\Patch(
        path: '/catalog/schema/{schemaId}',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/MethodFormSchemaCreate',
                type: 'object',
            )
        ),
        tags: ['catalog/schema'],
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
    public function update(
        DataResponseFactoryInterface $responseFactory,
        CatalogSchemeService $catalogSchemeService,
        RequestProviderInterface $requestProvider,
        #[RouteArgument('schemaId')]
        int $schemaId
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $catalogSchemeService->persist(array_merge($payload, ['id' => $schemaId]));

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Delete(
        path: '/catalog/schema/{schemaId}',
        description: '',
        tags: ['catalog/schema'],
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
    public function delete(
        DataResponseFactoryInterface $responseFactory,
        CatalogSchemeService $catalogSchemeService,
        #[RouteArgument('schemaId')]
        int $schemaId
    ): ResponseInterface {

        $catalogSchemeService->delete($schemaId);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }
}
