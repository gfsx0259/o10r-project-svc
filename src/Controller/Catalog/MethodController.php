<?php

declare(strict_types=1);

namespace App\Controller\Catalog;

use App\Entity\Method;
use App\Repository\MethodRepository;
use App\Service\CatalogMethodService;
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
    name: 'catalog/method',
    description: 'Catalog method API'
)]
final readonly class MethodController
{
    public function __construct(
        private MethodRepository $methodRepository,
    ) {}

    #[OA\Get(
        path: '/catalog/method',
        description: '',
        tags: ['catalog/method'],
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
                                    items: new OA\Items(ref: '#/components/schemas/Method'),
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
    ): ResponseInterface
    {
        $methods = $this->methodRepository->findAll();

        return $responseFactory->createResponse(array_map(fn (Method $method) => [
            'id' => $method->getId(),
            'code' => $method->getCode(),
            'title' => $method->getTitle(),
            'description' => $method->getDescription(),
        ], $methods));
    }

    #[OA\Post(
        path: '/catalog/method',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/MethodCreate',
                type: 'object',
            )
        ),
        tags: ['catalog/method'],
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
                                    ref: '#/components/schemas/Method',
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
        CatalogMethodService $catalogMethodService,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $method = $catalogMethodService->persist($payload);

        return $responseFactory->createResponse([
            'id' => $method->getId(),
            'code' => $method->getCode(),
            'title' => $method->getTitle(),
            'description' => $method->getDescription(),
        ], Status::CREATED);
    }

    #[OA\Patch(
        path: '/catalog/method/{methodId}',
        description: '',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/MethodCreate',
                type: 'object',
            )
        ),
        tags: ['catalog/method'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Method updated successfully',
                content: new JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                    ]
                )
            )
        ],
    )]
    #[Parameter(
        parameter: 'methodId',
        name: 'methodId',
        description: 'Method Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function update(
        DataResponseFactoryInterface $responseFactory,
        CatalogMethodService $catalogMethodService,
        RequestProviderInterface $requestProvider,
        #[RouteArgument('methodId')]
        int $methodId
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $catalogMethodService->persist(array_merge($payload, ['id' => $methodId]));

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }

    #[OA\Delete(
        path: '/catalog/method/{methodId}',
        description: '',
        tags: ['catalog/method'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Method removed successfully',
            )
        ],
    )]
    #[Parameter(
        parameter: 'methodId',
        name: 'methodId',
        description: 'Method Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer'),
        example: 11,
    )]
    public function delete(
        DataResponseFactoryInterface $responseFactory,
        CatalogMethodService $catalogMethodService,
        #[RouteArgument('methodId')]
        int $methodId
    ): ResponseInterface {

        $catalogMethodService->delete($methodId);

        return $responseFactory->createResponse(null, Status::NO_CONTENT);
    }
}
