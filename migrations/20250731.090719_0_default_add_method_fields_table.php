<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultB21b9b18065aa4461ca8c5b0ac174e99 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('method_form_schema')
            ->addColumn('id', 'primary')
            ->addColumn('method_id', 'integer')
            ->addColumn('fields', 'json')
            ->create();
    }

    public function down(): void
    {
        $this->table('method_form_schema')
            ->drop();
    }
}
