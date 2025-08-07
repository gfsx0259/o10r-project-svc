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
            ->addColumn('conditions', 'json')
            ->create();

        $this->table('scenario')
            ->addColumn('id', 'primary')
            ->addColumn('route_id', 'integer')
            ->addColumn('title', 'string')
            ->addColumn('conditions', 'json')
            ->create();

        $this->table('callback')
            ->addColumn('id', 'primary')
            ->addColumn('scenario_id', 'integer')
            ->addColumn('body', 'json')
            ->addColumn('order', 'tinyint')
            ->create();
    }

    public function down(): void
    {
        $this->table('route')
            ->drop();
        $this->table('scenario')
            ->drop();
        $this->table('callback')
            ->drop();
    }
}
