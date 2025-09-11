<?php

declare(strict_types=1);

use App\Fixture\FixtureLoader;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Injector\Injector;

return [
    FixtureLoader::class => function (Aliases $aliases, Injector $injector) {
        return $injector->make(FixtureLoader::class, [
            'methodDefinitions' => require $aliases->get('@fixture/methods.php'),
            'schemaDefinitions' => require $aliases->get('@fixture/schemas.php'),
            'routeDefinitions' => require $aliases->get('@fixture/routes.php'),
            'resourceDefinitions' => require $aliases->get('@fixture/static.php'),
        ]);
    }
];
