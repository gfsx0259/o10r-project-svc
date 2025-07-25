<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Header;

final class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response
            ->withHeader(Header::ALLOW, '*')
            ->withHeader(Header::VARY, 'Origin')
            ->withHeader(Header::ACCESS_CONTROL_ALLOW_ORIGIN, 'https://welcome.o10r.io')
            ->withHeader(Header::ACCESS_CONTROL_ALLOW_METHODS, 'GET,OPTIONS,HEAD,POST,PUT,PATCH,DELETE')
            ->withHeader(Header::ACCESS_CONTROL_ALLOW_HEADERS, 'content-type')
            ->withHeader(Header::ACCESS_CONTROL_EXPOSE_HEADERS, '*')
            ->withHeader(Header::ACCESS_CONTROL_ALLOW_CREDENTIALS, 'true')
            ->withHeader(Header::ACCESS_CONTROL_MAX_AGE, '86400');
    }
}
