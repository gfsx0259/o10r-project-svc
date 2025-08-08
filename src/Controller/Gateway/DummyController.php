<?php

declare(strict_types=1);

namespace App\Controller\Gateway;

use App\Module\Dummy\Callback\CallbackResolver;
use App\Module\Dummy\State;
use App\Module\Dummy\StateManager;
use App\Repository\MethodRepository;
use App\Repository\ProjectRepository;
use App\Repository\RouteRepository;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Tag;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;
use Yiisoft\RequestProvider\RequestProviderInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

#[SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'apiKey',
    description: 'Вводите токен в формате: Bearer {token}',
    name: 'Authorization',
    in: 'header'
)]
#[Tag(
    name: 'gateway/dummy',
    description: 'Dummy gateway API'
)]
#[OA\Schema(
    schema: 'PaymentRequestGeneralSection',
    properties: [
        new OA\Property(property: 'payment_id', type: 'integer', example: 'EP_100ASDF'),
        new OA\Property(property: 'project_id', type: 'integer', example: 500),
        new OA\Property(property: 'method_code', type: 'string', example: 'card'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaymentRequestPaymentSection',
    properties: [
        new OA\Property(property: 'amount', type: 'integer', example: 1000),
        new OA\Property(property: 'currency', type: 'string', example: 'RUB'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaymentRequest',
    properties: [
        new OA\Property(property: 'general', ref: '#/components/schemas/PaymentRequestGeneralSection', type: 'object'),
        new OA\Property(property: 'payment', ref: '#/components/schemas/PaymentRequestPaymentSection', type: 'object'),
    ],
    type: 'object'
)]
class DummyController
{
    #[OA\Post(
        path: '/gateway/access/{projectId}',
        description: 'We should proxy merchant credentials to gateway, ensure that merchant has access to specified project. Create session if it is ok.',
        security: [['bearerAuth' => []]],
        tags: ['gateway/dummy'],
        responses: [
            new OA\Response(response: '200', description: 'Success'),
            new OA\Response(response: '403', description: 'Forbidden'),
            new OA\Response(response: '404', description: 'Project not found'),
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
    public function access(
        ServerRequestInterface $serverRequest,
        DataResponseFactoryInterface $responseFactory,
        ProjectRepository $projectRepository,
        #[RouteArgument('projectId')]
        int $projectId,
    ): ResponseInterface {
        $project = $projectRepository->getById($projectId);

        $actualSecretKey = $serverRequest->getHeader('Authorization')[0] ?? '';
        $expectedSecretKey = $project->getSecretKey();

        return $actualSecretKey === $expectedSecretKey
            ? $responseFactory->createResponse()
            : $responseFactory->createResponse(null, Status::FORBIDDEN);
    }

    #[OA\Post(
        path: '/gateway/payment',
        description: 'Accept payment from trusted session service by internal network',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/PaymentRequest',
                type: 'object',
            )
        ),
        tags: ['gateway/dummy'],
        responses: [
            new OA\Response(response: '200', description: 'Success'),
            new OA\Response(response: '404', description: 'Project not found'),
        ]
    )]
    public function payment(
        DataResponseFactoryInterface $responseFactory,
        RouteRepository $routeRepository,
        MethodRepository $methodRepository,
        RequestProviderInterface $requestProvider,
        StateManager $stateManager,
    ): ResponseInterface {
        $initialRequest = $requestProvider->get()->getParsedBody();

        $method = $methodRepository->getByCode(ArrayHelper::getValueByPath($initialRequest, 'general.method_code'));

        if (!$route = $routeRepository->getByMethod($method->getId())) {
            $responseFactory
                ->createResponse()
                ->withStatus(Status::NOT_FOUND);
        }

        $paymentId = ArrayHelper::getValueByPath($initialRequest, 'general.payment_id');

        $state = new State(
            $paymentId,
            $route->getId(),
            $initialRequest
        );

        $stateManager->save($state);

        $responseData = [
            'status' => 'success',
            'project_id' => $state->getInitialRequest()->get('general.project_id'),
            'payment_id' => $state->getInitialRequest()->get('general.payment_id'),
        ];

        return $responseFactory
            ->createResponse($responseData);
    }


    #[OA\Get(
        path: '/gateway/payment/{paymentId}',
        description: 'Get payment details by payment id',
        tags: ['gateway/dummy'],
        responses: [
            new OA\Response(response: '200', description: 'Success'),
            new OA\Response(response: '404', description: 'Project not found'),
        ]
    )]
    #[Parameter(
        parameter: 'paymentId',
        name: 'paymentId',
        description: 'Payment Id',
        in: 'path',
        required: true,
        schema: new Schema(type: 'string'),
        example: 11,
    )]
    public function status(
        DataResponseFactoryInterface $responseFactory,
        StateManager $stateManager,
        CallbackResolver $callbackResolver,
        #[RouteArgument('paymentId')]
        string $paymentId,
    ): ResponseInterface {
        if (!$state = $stateManager->get($paymentId)) {
            return $this->responseNotFound($responseFactory);
        }

        $callback = $callbackResolver->resolve($state);

        return $responseFactory
            ->createResponse(json_decode($callback->getBody(), true));
    }

    private function responseNotFound(DataResponseFactoryInterface $responseFactory): ResponseInterface
    {
        return $responseFactory
            ->createResponse([
                'payment' => ['status' => 'error'],
                'errors' => [
                    ['code' => '3061', 'message' => 'Transaction not found'],
                ],
            ]);
    }
}
