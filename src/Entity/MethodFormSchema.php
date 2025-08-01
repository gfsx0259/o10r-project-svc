<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MethodFormSchemaRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(repository: MethodFormSchemaRepository::class, table: 'method_form_schema', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'integer', name: 'method_id')]
#[Column(type: 'json', name: 'fields')]
class MethodFormSchema
{
    private int $id;
    private int $method_id;
    private string $fields;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMethodId(): int
    {
        return $this->method_id;
    }

    public function getFields(): string
    {
        return $this->fields;
    }
    public function setMethodId(int $method_id): void
    {
        $this->method_id = $method_id;
    }

    public function setFields(string $fields): void
    {
        $this->fields = $fields;
    }

}
