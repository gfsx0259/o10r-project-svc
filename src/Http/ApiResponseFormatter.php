<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponse;
use Yiisoft\DataResponse\DataResponseFormatterInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;

final readonly class ApiResponseFormatter implements DataResponseFormatterInterface
{
    public function __construct(
        private ApiResponseDataFactory $apiResponseDataFactory,
        private JsonDataResponseFormatter $jsonDataResponseFormatter
    ) {}

    public function format(DataResponse $dataResponse): ResponseInterface
    {
        if (!$dataResponse->hasData()) {
            return $dataResponse->getResponse();
        }

        $response = $dataResponse->withData(
            $this->apiResponseDataFactory
                ->createFromResponse($dataResponse)
                ->toArray(),
        );

        return $this->jsonDataResponseFormatter->format($response);
    }
}
