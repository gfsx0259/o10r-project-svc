<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefault0551ec508712b5701c7f025a60f2caf9 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('project_setting')
            ->addColumn('id', 'primary')
            ->addColumn('project_id', 'integer')
            ->addColumn('code', 'string')
            ->addColumn('value', 'string')
            ->addColumn('group', 'tinyInt')
            ->create();
    }

    public function down(): void
    {
        $this->table('project_setting')
            ->drop();
    }
}
