<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

// TODO It`s incorrect, methods should be path of catalog service, it`s just dummy to prototype
class OrmDefault7ba3888cb802b898331518a67da80c90 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->database()
            ->insert('method')
            ->columns(['code', 'title', 'description'])
            ->values([
                ['card', 'Card', 'Pay using bank cards'],
                ['enthusiast', 'Enthusiast pay', 'Pay using enthusiast wallet']
            ])
            ->run();
    }

    public function down(): void
    {
        $this->database()
            ->delete('method');
    }
}
