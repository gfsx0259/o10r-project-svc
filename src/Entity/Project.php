<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\ManyToMany;

#[Entity(repository: ProjectRepository::class, table: 'project', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'string', name: 'user_id', property: 'userId')]
#[Column(type: 'string', name: 'secret_key', property: 'secretKey')]
#[Column(type: 'string', name: 'code')]
#[Column(type: 'integer', name: 'is_sandbox', property: 'isSandbox')]
class Project
{
    private int $id;
    private string $code;
    private string $userId;
    private string $secretKey;
    private int $isSandbox;

    #[ManyToMany(
        target: Method::class,
        through: ProjectMethod::class,
        innerKey: 'id',
        outerKey: 'id',
        throughInnerKey: 'project_id',
        throughOuterKey: 'method_id'
    )]
    private array $methods = [];


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

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function isSandbox(): int
    {
        return $this->isSandbox;
    }

    public function setIsSandbox(int $isSandbox): void
    {
        $this->isSandbox = $isSandbox;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }
}
