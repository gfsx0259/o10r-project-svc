<?php

declare(strict_types=1);

namespace App\Controller\Gateway;

use App\Exception\NotFoundException;
use App\Http\RedirectResponseFactory;
use App\Module\Dummy\Action\ActionPicker;
use App\Module\Dummy\Action\ApsAction;
use App\Module\Dummy\Callback\CallbackCollectionProvider;
use App\Module\Dummy\Callback\CallbackProcessor;
use App\Module\Dummy\Collection\ArrayCollection;
use App\Module\Dummy\State;
use App\Module\Dummy\StateManager;
use App\Repository\MethodRepository;
use App\Repository\ProjectRepository;
use App\Repository\RouteRepository;
use LogicException;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Tag;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;
use Yiisoft\RequestProvider\RequestProviderInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

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
    required: ['payment_id', 'project_id'],
    properties: [
        new OA\Property(property: 'payment_id', type: 'integer', example: 'EP_100ASDF'),
        new OA\Property(property: 'project_id', type: 'integer', example: 500),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaymentRequestPaymentSection',
    required: ['amount', 'currency', 'method'],
    properties: [
        new OA\Property(property: 'amount', type: 'integer', example: 1000),
        new OA\Property(property: 'currency', type: 'string', example: 'RUB'),
        new OA\Property(property: 'method', type: 'string', example: 'enthusiast'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaymentRequestMethodSection',
    properties: [
        new OA\Property(
            property: 'card',
            properties: [
                new OA\Property(property: 'return_url', type: 'string', format: 'uri')
            ],
            type: 'object'
        )
    ],
    type: 'object',
)]
#[OA\Schema(
    schema: 'PaymentRequest',
    required: ['general', 'payment'],
    properties: [
        new OA\Property(property: 'general', ref: '#/components/schemas/PaymentRequestGeneralSection', type: 'object'),
        new OA\Property(property: 'payment', ref: '#/components/schemas/PaymentRequestPaymentSection', type: 'object'),
        new OA\Property(property: 'method', ref: '#/components/schemas/PaymentRequestMethodSection', type: 'object'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CompleteRequest',
    properties: [
        new OA\Property(property: 'general', ref: '#/components/schemas/PaymentRequestGeneralSection', type: 'object'),
        new OA\Property(property: 'md', type: 'string', example: 'bb4f6b9a6c651d0e1f2d8ddfc4232b00'),
    ],
    type: 'object'
)]
class DummyController
{
    private const string
        STATUS_UNPAID = 'unpaid',
        STATUS_PROCESSING = 'processing',
        STATUS_REDIRECT = 'redirect',
        STATUS_SUCCESS = 'success',
        STATUS_DECLINE = 'decline';

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

    /**
     * @throws NotFoundException
     */
    #[OA\Post(
        path: '/gateway/payment',
        description: 'Accept payment from trusted session service by internal network. Create state and response with processing status.',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/PaymentRequest',
                type: 'object',
            )
        ),
        tags: ['gateway/dummy'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/Status',
                                type: 'object',
                            ),
                        ]),
                    ]
                ),
            ),
            new OA\Response(response: '404', description: 'Project not found'),
        ]
    )]
    public function payment(
        DataResponseFactoryInterface $responseFactory,
        RouteRepository $routeRepository,
        MethodRepository $methodRepository,
        RequestProviderInterface $requestProvider,
        CallbackCollectionProvider $callbackCollectionProvider,
        StateManager $stateManager,
    ): ResponseInterface {
        $initialRequest = new ArrayCollection($requestProvider->get()->getParsedBody());

        $method = $methodRepository->getByCode($initialRequest->get('payment.method_code'));
        $paymentId = $initialRequest->get('general.payment_id');

        if (!$route = $routeRepository->getByMethod($method->getId())) {
            $responseFactory
                ->createResponse()
                ->withStatus(Status::NOT_FOUND);
        }

        $state = new State(
            $paymentId,
            $route->getId(),
            $initialRequest->data,
            $callbackCollectionProvider->provide($route->getId(), $initialRequest->data)
        );

        $stateManager->save($state);

        return $responseFactory
            ->createResponse([
                'payment' => ['status' => self::STATUS_PROCESSING]
            ]);
    }

    #[OA\Get(
        path: '/gateway/payment/{paymentId}',
        description: 'Get payment details by payment id',
        tags: ['gateway/dummy'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Success',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/Response'),
                        new OA\Schema(properties: [
                            new OA\Property(
                                property: 'data',
                                ref: '#/components/schemas/Status',
                                type: 'object',
                            ),
                        ]),
                    ]
                ),
            ),
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
        CallbackProcessor $callbackProcessor,
        #[RouteArgument('paymentId')]
        string $paymentId,
    ): ResponseInterface {
        if (!$state = $stateManager->get($paymentId)) {
            return $responseFactory
                ->createResponse([
                    'payment' => ['status' => self::STATUS_UNPAID]
                ]);
        }
        $callback = $callbackProcessor->process($state);

        return $responseFactory
            ->createResponse($callback->data);
    }

    #[OA\Post(
        path: '/gateway/complete',
        description: 'Complete action',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/CompleteRequest',
                type: 'object',
            )
        ),
        tags: ['gateway/dummy'],
        responses: [
            new OA\Response(response: '200', description: 'Success'),
            new OA\Response(response: '404', description: 'Project not found'),
        ]
    )]
    #[Parameter(
        parameter: 'uniqueKey',
        name: 'uniqueKey',
        description: 'Unique key',
        in: 'query',
        schema: new Schema(type: 'string'),
        example: 11,
    )]
    public function complete(
        RedirectResponseFactory $redirectResponseFactory,
        DataResponseFactoryInterface $dataResponseFactory,
        StateManager $stateManager,
        ActionPicker $actionPicker,
        RequestProviderInterface $requestProvider,
    ): ResponseInterface
    {
        $requestParams = array_merge(
            $requestProvider->get()->getQueryParams(),
            $requestProvider->get()->getParsedBody() ?? [],
        );

        $payload = new ArrayCollection($requestParams);

        if (!$action = $actionPicker->pickCompleted($payload)) {
            throw new LogicException('Can not find action to complete');
        }

        $actionKey = $action->resolveCompletedKey($payload);

        if (!$state = $stateManager->restore($actionKey)) {
            if (!$state = $stateManager->get($payload->get('general.payment_id'))) {
                throw new LogicException('State must be exists');
            }
        }

        if (!$state->isActionCompleted($actionKey)) {
            $state->completeAction($actionKey);
            $state->next();
        }

        $stateManager->save($state);

        return $action instanceof ApsAction
            ? $redirectResponseFactory->createResponse($state->getInitialRequest()->get('return_url.default'))
            : $dataResponseFactory->createResponse();
    }

    #[Parameter(
        parameter: 'page',
        name: 'page',
        description: 'page',
        in: 'path',
        required: true,
        schema: new Schema(type: 'string'),
        example: 11,
    )]
    public function proxy(
        DataResponseFactoryInterface $responseFactory,
        RequestProviderInterface $requestProvider,
        UrlGeneratorInterface $urlGenerator,
        #[RouteArgument('page')]
        string $page
    ): ResponseInterface {
        $payload = $requestProvider->get()->getParsedBody();

        $redirectTo = $_ENV['DUMMY_UI_HOST'] . $urlGenerator->generate('action/dummy', ['page' => $page], $payload);

        return $responseFactory
            ->createResponse(code: 302)
            ->withHeader('Location', $redirectTo);
    }
}
