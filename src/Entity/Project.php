<?php

declare(strict_types=1);

namespace App\Entity;

use App\Collection\ProjectMethodCollection;
use App\Collection\ProjectSettingCollection;
use App\Repository\ProjectRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(repository: ProjectRepository::class, table: 'project', readonlySchema: true)]
#[Column(type: 'primary', name: 'id')]
#[Column(type: 'string', name: 'user_id', property: 'userId')]
#[Column(type: 'string', name: 'secret_key', property: 'secretKey')]
#[Column(type: 'string', name: 'code')]
#[Column(type: 'string', name: 'hash')]
#[Column(type: 'integer', name: 'is_sandbox', property: 'isSandbox')]
class Project
{
    private int $id;
    private string $code;
    private string $hash;
    private string $userId;
    private string $secretKey;
    private int $isSandbox;

    #[HasMany(
        target: ProjectMethod::class,
        innerKey: 'id',
        outerKey: 'project_id',
        collection: ProjectMethodCollection::class
    )]
    private ProjectMethodCollection $methods;

    #[HasMany(
        target: ProjectSetting::class,
        innerKey: 'id',
        outerKey: 'project_id',
        collection: ProjectSettingCollection::class,

    )]
    private ProjectSettingCollection $settings;

    public function __construct()
    {
        $this->methods = new ProjectMethodCollection();
        $this->settings = new ProjectSettingCollection();
    }

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

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
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

    public function getMethods(): ProjectMethodCollection
    {
        return $this->methods;
    }

    public function getSettings(): ProjectSettingCollection
    {
        return $this->settings;
    }
}
