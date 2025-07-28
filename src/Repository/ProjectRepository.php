<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Cycle\ORM\Select\Repository;

final class ProjectRepository extends Repository
{
    public function getById(int $id, array $includes = []): Project
    {
        return $this->findByPK($id);
    }

    public function findByUserId(string $userId): array
    {
        return $this->findAll(['user_id' => $userId]);
    }
}
