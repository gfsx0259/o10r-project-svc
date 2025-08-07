<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Collection\ProjectMethodCollection;
use App\Collection\ProjectSettingCollection;
use App\Entity\Project\Project;
use App\Entity\Project\ProjectSetting;
use App\Repository\ProjectSettingRepository;

final readonly class ProjectDtoAssembler
{
    private const string
        INCLUDE_METHOD_KEY = 'method',
        INCLUDE_SETTING_KEY = 'setting';

    public function assemble(Project $project, array $includes = []): ProjectDto
    {
        $methods = in_array(self::INCLUDE_METHOD_KEY, $includes) ? $project->getMethods() : new ProjectMethodCollection();
        $settings = in_array(self::INCLUDE_SETTING_KEY, $includes) ? $project->getSettings() : new ProjectSettingCollection();

        return new ProjectDto(
            $project->getId(),
            $project->getCode(),
            $project->getSecretKey(),
            $project->getHash(),
            $project->isSandbox(),
            $methods->getIds(),
            array_map(fn (ProjectSetting $projectSetting) => [
                'code' => $projectSetting->getCode(),
                'value' => $projectSetting->getValue(),
                'group' => ProjectSettingRepository::SETTING_GROUP_CODE_MAP[$projectSetting->getGroup()],
            ], $settings->toArray()),
        );
    }
}
