<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefault004b8dfbff6c0f83a481e7913be8c04b extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('project_method')
            ->addColumn('project_id', 'integer')
            ->addColumn('method_id', 'integer')
            ->create();
    }

    public function down(): void
    {
        $this->table('project_method')
            ->drop();
    }
}
