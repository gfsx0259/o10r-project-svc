<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Header;

/**
 * Parse merchant token to ensure project access
 */
final class AuthentificationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $tokenHeader = $request->getHeader(Header::AUTHORIZATION)[0] ?? null;

        if (!$tokenHeader) {
            return $handler->handle($request);
        }

        $token = $this->parseBearerToken($tokenHeader);

        return $handler->handle(
            $request
                ->withAttribute('token', $token)
        );
    }

    private function parseBearerToken(string $token): ?string
    {
        if (str_starts_with($token, 'Bearer ')) {
            return substr($token, 7);
        }

        return null;
    }
}
