<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\Project\ProjectSetting;
use App\Repository\ProjectSettingRepository;

final readonly class SettingDtoAssembler
{
    public function assemble(ProjectSetting $projectSetting): SettingDto
    {
        return new SettingDto(
            $projectSetting->getCode(),
            $projectSetting->getValue(),
            ProjectSettingRepository::SETTING_GROUP_CODE_MAP[$projectSetting->getGroup()],
        );
    }
}
