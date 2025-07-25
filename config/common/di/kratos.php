<?php

declare(strict_types=1);

use Ory\Client\Api\FrontendApi;

/** @var array $params */

$config = Ory\Client\Configuration::getDefaultConfiguration()->setHost('http://kratos:4433/');

return [
    FrontendApi::class => [
        '__construct()' => [
            'config' => $config
        ],
    ],
];

