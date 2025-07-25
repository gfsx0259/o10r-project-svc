<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultD4166f7bf4eba1a197d916fc329c5464 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('project')
            ->addColumn('id', 'primary')
            ->addColumn('code', 'tinyText')
            ->addColumn('user_id', 'tinyText')
            ->addColumn('secret_key', 'tinyText')
            ->create();
    }

    public function down(): void
    {
        $this->table('project')
            ->drop();
    }
}
