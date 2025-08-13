<?php

declare(strict_types=1);

use App\Module\Dummy\Callback\OverrideProcessor;

return [
    OverrideProcessor::class => [
        'class' => OverrideProcessor::class,
        '__construct()' => [
            'host' => $_ENV['DUMMY_UI_HOST'],
        ],
    ],
];
