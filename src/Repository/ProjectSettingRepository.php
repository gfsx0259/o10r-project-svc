<?php

declare(strict_types=1);

namespace App\Repository;

use Cycle\ORM\Select\Repository;

final class ProjectSettingRepository extends Repository
{
    const int SETTING_GROUP_NAVIGATION = 1;
    const int SETTING_GROUP_APPEARANCE = 2;
    const int SETTING_GROUP_BEHAVIOUR = 3;

    const array SETTING_GROUP_CODE_MAP = [
        self::SETTING_GROUP_NAVIGATION => 'navigation',
        self::SETTING_GROUP_APPEARANCE => 'appearance',
        self::SETTING_GROUP_BEHAVIOUR => 'behaviour',
    ];

    public function findByProjectId(int $projectId): array
    {
        return $this->findAll(['project_id' => $projectId]);
    }
}
