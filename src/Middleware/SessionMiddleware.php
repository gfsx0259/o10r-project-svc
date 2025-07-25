<?php

declare(strict_types=1);

namespace App\Middleware;

use Exception;
use Ory\Client\Api\FrontendApi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Yiisoft\DataResponse\DataResponseFactoryInterface;

final class SessionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private DataResponseFactoryInterface $dataResponseFactory,
        public FrontendApi $ory,
    ) {}

    /**
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $cookies = "";
        foreach ($_COOKIE as $key => $value) {
            $cookies .= "$key=$value;";
        }

        $session = $this->ory->toSession("", $cookies);

        if (!$session["active"]) throw new Exception('Session expired');

        return $handler->handle(   $request->withAttribute('session', $session));
    }
}
