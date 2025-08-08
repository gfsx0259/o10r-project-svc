<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project\Project;
use App\Exception\NotFoundException;
use Cycle\ORM\Select\Repository;

final class ProjectRepository extends Repository
{
    public function getById(int $id): Project
    {
        if (!$project = $this->findByPK($id)) {
            throw new NotFoundException();
        }

        return $project;
    }

    /**
     * @param string $hash
     * @return Project
     * @throws NotFoundException
     */
    public function getByHash(string $hash): Project
    {
        if (!$project = $this->findOne(['hash' => $hash])) {
            throw new NotFoundException();
        }

        return $project;
    }

    public function findByUserId(string $userId): array
    {
        return $this->findAll(['user_id' => $userId]);
    }
}
