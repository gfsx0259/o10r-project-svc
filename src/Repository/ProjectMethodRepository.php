<?php

declare(strict_types=1);

namespace App\Repository;

use Cycle\Database\DatabaseManager;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;

final class ProjectMethodRepository extends Repository
{
    public function __construct(
        Select $select,
        private DatabaseManager $dbal
    ) {
        parent::__construct($select);
    }

    public function findByProjectId(int $projectId): array
    {
        return $this->findAll(['project_id' => $projectId]);
    }

    public function deleteProjectMethods(int $projectId): void {
        $this->dbal->database('default')->table('project_method')
            ->delete()
            ->where('project_id', $projectId)
            ->run();
    }
}
