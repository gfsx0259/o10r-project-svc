<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;

final readonly class RedirectResponseFactory
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
    ) {}

    public function createResponse(string $url): ResponseInterface
    {
        return $this
            ->responseFactory
            ->createResponse()
            ->withStatus(Status::FOUND)
            ->withHeader(Header::LOCATION, $url);
    }
}
