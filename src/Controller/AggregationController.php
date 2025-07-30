<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\PaymentPageDtoAssembler;
use App\Repository\ProjectRepository;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Tag;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

final readonly class AggregationController
{
    #[Tag(
        name: 'aggregation',
        description: 'Project service aggregation API'
    )]
    public function __construct(
        private ProjectRepository $projectRepository,
        private PaymentPageDtoAssembler $paymentPageDtoAssembler,
    ) {}

    #[OA\Get(
        path: '/aggregation/payment-page/{projectHash}',
        summary: 'Return aggregated project data',
        tags: ['aggregation'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Return project',
                content: new OA\JsonContent(
                    allOf: [
                        new Schema(ref: '#/components/schemas/Response'),
                        new Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    ref: '#/components/schemas/PaymentPageDto'
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    #[Parameter(
        parameter: 'projectHash',
        name: 'projectHash',
        description: 'Project hash',
        in: 'path',
        required: true,
        schema: new Schema(type: 'string'),
        example: '03d59e663c1af9ac33a9949d1193505a',
    )]
    public function paymentPage(
        DataResponseFactoryInterface $responseFactory,
        #[RouteArgument('projectHash')]
        string $projectHash,
    ): ResponseInterface {
        $project = $this->projectRepository->getByHash($projectHash);
        return $responseFactory->createResponse(
            (array) $this->paymentPageDtoAssembler->assemble($project)
        );
    }
}
