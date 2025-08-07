<?php

declare(strict_types=1);

namespace App\Entity\Project;

use App\Repository\ProjectMethodRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: ProjectMethodRepository::class, table: 'project_method')]
#[Column(type: 'tinyInteger', name: 'project_id', primary: true)]
#[Column(type: 'tinyInteger', name: 'method_id', primary: true)]
class ProjectMethod
{
    private int $project_id;
    private int $method_id;

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): void
    {
        $this->project_id = $project_id;
    }

    public function getMethodId(): int
    {
        return $this->method_id;
    }

    public function setMethodId(int $method_id): void
    {
        $this->method_id = $method_id;
    }
}
