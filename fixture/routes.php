<?php

use App\Fixture\Gateway\RouteDto;
use App\Fixture\Gateway\ScenarioDto;

return [
    new RouteDto(
        'card',
        scenarios: [
            new ScenarioDto('Success', callbacks: [
                'data/routes/card/success.json',
            ]),
            new ScenarioDto('Decline', callbacks: [
                'data/routes/card/fail.json',
            ]),
            new ScenarioDto('3ds', callbacks: [
                'data/routes/card/3ds-0.json',
                'data/routes/card/3ds-1.json',
            ]),
            new ScenarioDto('3ds Cascading', callbacks: [
                'data/routes/card/3ds-cascading-0.json',
                'data/routes/card/3ds-cascading-1.json',
            ]),
            new ScenarioDto('3ds2', callbacks: [
                'data/routes/card/3ds2-0.json',
                'data/routes/card/3ds2-1.json',
                'data/routes/card/3ds2-2.json',
            ]),
            new ScenarioDto('3ds2 Cascading', callbacks: [
                'data/routes/card/3ds2-cascading-0.json',
                'data/routes/card/3ds2-cascading-1.json',
                'data/routes/card/3ds2-cascading-2.json',
            ]),
            new ScenarioDto('Clarification', callbacks: [
                'data/routes/card/clara-0.json',
                'data/routes/card/clara-1.json',
            ])
        ],
    ),
    new RouteDto(
        'enthusiast',
        scenarios: [
            new ScenarioDto('Success', callbacks: [
                'data/routes/enthusiast/success-0.json',
                'data/routes/enthusiast/success-1.json',
            ]),
            new ScenarioDto('Decline', callbacks: [
                'data/routes/enthusiast/fail-0.json',
                'data/routes/enthusiast/fail-1.json',
            ]),
        ]
    ),
];
