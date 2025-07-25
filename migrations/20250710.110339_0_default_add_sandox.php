<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefault5b84758b7c17212e6afb6b83a95e29f8 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('project')
            ->addColumn('is_sandbox', 'bool')
            ->update();
    }

    public function down(): void
    {
        $this->table('resource')
            ->dropColumn('is_sandbox')
            ->update();
    }
}
