<?php

declare(strict_types=1);

namespace App\Entity\Project;

use App\Repository\ProjectSettingRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: ProjectSettingRepository::class, table: 'project_setting', readonlySchema: true)]
#[Column(type: 'primary', name: 'id',  primary: true)]
#[Column(type: 'primary', name: 'project_id', primary: true)]
#[Column(type: 'string', name: 'code')]
#[Column(type: 'string', name: 'value')]
#[Column(type: 'integer', name: 'group')]
class ProjectSetting
{
    private int $id;
    private int $project_id;
    private string $code;
    private string $value;
    private int $group;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProjectId(): int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): void
    {
        $this->project_id = $project_id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $title): void
    {
        $this->value = $title;
    }

    public function getGroup(): int
    {
        return $this->group;
    }

    public function setGroup(int $group): void
    {
        $this->group = $group;
    }
}
