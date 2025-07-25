<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefault196c43a8cc8cf109e371a546f8a5e3ed extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('method')
            ->addColumn('id', 'primary')
            ->addColumn('code', 'tinyText')
            ->addColumn('title', 'tinyText')
            ->addColumn('description', 'tinyText')
            ->create();
    }

    public function down(): void
    {
        $this->table('method')
            ->drop();
    }
}
