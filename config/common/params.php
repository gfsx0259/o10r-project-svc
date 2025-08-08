<?php

declare(strict_types=1);

use Cycle\Database\Config\MySQL\TcpConnectionConfig;
use Cycle\Database\Config\MySQLDriverConfig;
use Cycle\Schema\Provider\FromFilesSchemaProvider;
use Cycle\Schema\Provider\SimpleCacheSchemaProvider;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Cache\Memcached\Memcached;
use Yiisoft\Definitions\Reference;
use Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider;

return [
    'supportEmail' => 'support@example.com',

    'yiisoft/aliases' => [
        'aliases' => [
            '@root' => dirname(__DIR__, 2),
            '@assets' => '@public/assets',
            '@assetsUrl' => '@baseUrl/assets',
            '@baseUrl' => '/',
            '@data' => '@root/data',
            '@messages' => '@resources/messages',
            '@public' => '@root/public',
            '@resources' => '@root/resources',
            '@runtime' => '@root/runtime',
            '@src' => '@root/src',
            '@tests' => '@root/tests',
            '@views' => '@root/views',
            '@vendor' => '@root/vendor',
        ],
    ],

    'yiisoft/router-fastroute' => [
        'enableCache' => false,
    ],

    'yiisoft/view' => [
        'basePath' => '@views',
        'parameters' => [
            'assetManager' => Reference::to(AssetManager::class),
        ],
    ],

    'yiisoft/yii-swagger' => [
        'annotation-paths' => [
            '@src',
        ],
    ],


    // Общий конфиг Cycle
    'yiisoft/yii-cycle' => [
        // Конфиг Cycle DBAL
        'dbal' => [
            // PSR-3 совместимый логгер SQL запросов
            'query-logger' => null,
            // БД по умолчанию (из списка 'databases')
            'default' => 'default',
            'aliases' => [],
            'databases' => [
                'default' => ['connection' => 'project']
            ],
            'connections' => [
                // Пример настроек подключения к SQLite:
                'project' => new MySQLDriverConfig(
                    connection: new TcpConnectionConfig(
                        database: $_ENV['PP_DB_NAME'],
                        host: $_ENV['PP_DB_HOST'],
                        port: (int)$_ENV['PP_DB_PORT'],
                        user: $_ENV['PP_DB_USER'],
                        password: $_ENV['PP_DB_PASSWORD'],
                    )
                ),
            ],
        ],

        // Конфиг миграций
        'migrations' => [
            'directory' => '@root/migrations',
            'namespace' => 'App\\Migration',
            'table' => 'migration',
            'safe' => false,
        ],

        /**
         * Поставщики схемы реализуют класс {@see SchemaProviderInterface}.
         * Конфигурируется перечислением имён классов поставщиков. Вы здесь можете конфигурировать также и поставщиков,
         * указывая имя класса поставщика в качестве ключа элемента, а конфиг в виде массива элемента:
         */
        'schema-providers' => [
            SimpleCacheSchemaProvider::class => SimpleCacheSchemaProvider::config(
                key: 'my-custom-cache-key'
            ),
            FromFilesSchemaProvider::class => FromFilesSchemaProvider::config(
                files: ['@runtime/cycle-schema.php'],
            ),
            FromConveyorSchemaProvider::class,
        ],

        /**
         * Настройка для класса {@see \Yiisoft\Yii\Cycle\Schema\Conveyor\MetadataSchemaConveyor}.
         * Здесь указывается список папок с сущностями.
         * В путях поддерживаются псевдонимы {@see \Yiisoft\Aliases\Aliases}.
         */
        'entity-paths' => [
            '@src/Entity'
        ],

        'collections' => [
            /** Default factory (class or name from the `factories` list below) or {@see null} */
            'default' => 'doctrine',
            /** List of class names that implement {@see \Cycle\ORM\Collection\CollectionFactoryInterface} */
            'factories' => [
                'array' => Cycle\ORM\Collection\ArrayCollectionFactory::class,
                'doctrine' => \Cycle\ORM\Collection\DoctrineCollectionFactory::class,
                // 'illuminate' => \Cycle\ORM\Collection\IlluminateCollectionFactory::class,
            ],
        ],
    ],
    'yiisoft/cache-memcached' => [
        'memcached' => [
            'persistentId' => '',
            'servers' => [
                [
                    'host' => $_ENV['DUMMY_CACHE_HOST'],
                    'port' => $_ENV['DUMMY_CACHE_PORT'],
                    'weight' => Memcached::DEFAULT_SERVER_WEIGHT,
                ],
            ],
        ],
    ],
];
