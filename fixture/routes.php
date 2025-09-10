<?php

use App\Fixture\Gateway\RouteDto;
use App\Fixture\Gateway\ScenarioDto;

return [
    new RouteDto(
        'card',
        scenarios: [
            new ScenarioDto('Success', conditions: ['payment.amount' => 1000], callbacks: [
                'data/routes/card/success.json',
            ]),
            new ScenarioDto('Decline', conditions: ['payment.amount' => 1001], callbacks: [
                'data/routes/card/fail.json',
            ]),
            new ScenarioDto('3ds', ['payment.amount' => 1002], callbacks: [
                'data/routes/card/3ds-0.json',
                'data/routes/card/3ds-1.json',
            ]),
            new ScenarioDto('3ds Cascading', ['payment.amount' => 1003], callbacks: [
                'data/routes/card/3ds-cascading-0.json',
                'data/routes/card/3ds-cascading-1.json',
            ]),
            new ScenarioDto('3ds2', ['payment.amount' => 1004], callbacks: [
                'data/routes/card/3ds2-0.json',
                'data/routes/card/3ds2-1.json',
                'data/routes/card/3ds2-2.json',
            ]),
            new ScenarioDto('3ds2 Cascading', ['payment.amount' => 1005], callbacks: [
                'data/routes/card/3ds2-cascading-0.json',
                'data/routes/card/3ds2-cascading-1.json',
                'data/routes/card/3ds2-cascading-2.json',
            ]),
            new ScenarioDto('Clarification', ['payment.amount' => 1006], callbacks: [
                'data/routes/card/clara-0.json',
                'data/routes/card/clara-1.json',
            ])
        ],
    ),
    new RouteDto(
        'enthusiast',
        scenarios: [
            new ScenarioDto('Success', conditions: ['payment.amount' => 1000], callbacks: [
                'data/routes/enthusiast/success-0.json',
                'data/routes/enthusiast/success-1.json',
            ]),
            new ScenarioDto('Decline', conditions: ['payment.amount' => 1001], callbacks: [
                'data/routes/enthusiast/fail-0.json',
                'data/routes/enthusiast/fail-1.json',
            ]),
        ]
    ),
];
