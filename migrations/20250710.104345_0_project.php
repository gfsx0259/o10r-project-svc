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
            ->addColumn('code', 'string')
            ->addColumn('user_id', 'string')
            ->addColumn('secret_key', 'string')
            ->addColumn('hash', 'string')
            ->create();
    }

    public function down(): void
    {
        $this->table('project')
            ->drop();
    }
}
