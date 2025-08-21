<?php

declare(strict_types=1);

use App\Module\Dummy\Action\AcsAction;
use App\Module\Dummy\Action\ActionPicker;
use App\Module\Dummy\Action\ApsAction;
use App\Module\Dummy\Action\ClarificationAction;
use App\Module\Dummy\Action\FallbackAction;
use App\Module\Dummy\Callback\OverrideProcessor;

return [
    OverrideProcessor::class => [
        'class' => OverrideProcessor::class,
        '__construct()' => [
            'uiHost' => $_ENV['DUMMY_UI_HOST'],
            'selfHost' => $_ENV['DUMMY_SELF_HOST'],
        ],
    ],
    ActionPicker::class  => [
        'class' => ActionPicker::class,
        '__construct()' => [
            'actionClasses' => [
                ApsAction::class,
                AcsAction::class,
                ClarificationAction::class,
                FallbackAction::class,
            ]
        ]
    ]
];
