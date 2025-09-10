<?php

declare(strict_types=1);

use App\Command\FixtureCommand;

return [
    'yiisoft/yii-console' => [
        'commands' => [
            'fixture:load' => FixtureCommand::class,
        ]
    ]
];
