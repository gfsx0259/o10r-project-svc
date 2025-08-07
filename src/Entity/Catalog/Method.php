<?php

declare(strict_types=1);

namespace App\Entity\Catalog;

use App\Repository\MethodRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: MethodRepository::class, table: 'method', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'string', name: 'code')]
#[Column(type: 'string', name: 'title')]
#[Column(type: 'string', name: 'description')]
class Method
{
    private int $id;
    private string $code;
    private string $title;
    private string $description;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

}
