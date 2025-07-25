<?php

declare(strict_types=1);

namespace App\Repository;

use Cycle\ORM\Select\Repository;

final class ProjectRepository extends Repository
{
    public function findByUserId(string $userId): array
    {
        return $this->findAll(['user_id' => $userId]);
    }
}
