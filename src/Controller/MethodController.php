<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Method;
use App\Repository\MethodRepository;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;

final readonly class MethodController
{
    public function __construct(
        private MethodRepository $methodRepository,
    ) {}

    #[OA\Get(
        path: '/method',
        description: '',
        summary: 'Returns info about the API',
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new OA\JsonContent(
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
    public function index(
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
}
