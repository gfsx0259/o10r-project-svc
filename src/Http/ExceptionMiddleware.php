<?php

declare(strict_types=1);

namespace App\Http;

use App\Exception\ApplicationException;
use Ory\Client\ApiException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;
use Yiisoft\Input\Http\InputValidationException;

final class ExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private DataResponseFactoryInterface $dataResponseFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ApplicationException $e) {
            return $this->dataResponseFactory->createResponse($e->getMessage(), $e->getCode());
        } catch (InputValidationException $e) {
            return $this->dataResponseFactory->createResponse(
                $e->getResult()->getErrorMessages()[0],
                Status::BAD_REQUEST
            );
        } catch (ApiException $e) {
            return $this->dataResponseFactory->createResponse(
                $e->getMessage(),
                Status::FORBIDDEN
            );
        }
    }
}
