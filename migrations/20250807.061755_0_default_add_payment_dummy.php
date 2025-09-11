<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultF5844fca00ffb8bad6b877b3c0a80cf5 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('route')
            ->addColumn('id', 'primary')
            ->addColumn('method_id', 'integer')
            ->create();

        $this->table('scenario')
            ->addColumn('id', 'primary')
            ->addColumn('route_id', 'integer')
            ->addColumn('title', 'string')
            ->create();

        $this->table('callback')
            ->addColumn('id', 'primary')
            ->addColumn('scenario_id', 'integer')
            ->addColumn('body', 'json')
            ->addColumn('order', 'tinyint')
            ->create();

        $this->table('static')
            ->addColumn('id', 'primary')
            ->addColumn('name', 'string')
            ->addColumn('path', 'string')
            ->addColumn('body', 'json')
            ->create();
    }

    public function down(): void
    {
        $driver = $this->database()->getDriver();

        $driver->execute('DROP TABLE IF EXISTS `callback`');
        $driver->execute('DROP TABLE IF EXISTS `scenario`');
        $driver->execute('DROP TABLE IF EXISTS `route`');
        $driver->execute('DROP TABLE IF EXISTS `static`');
    }
}
