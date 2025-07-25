<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Project;

final readonly class ProjectDtoAssembler
{
    public function assemble(Project $project): ProjectDto
    {
        return new ProjectDto(
            $project->getId(),
            $project->getCode(),
            $project->getSecretKey(),
            $project->isSandbox(),
        );
    }
}
