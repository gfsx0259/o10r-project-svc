<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Entity\ProjectMethod;
use App\Entity\ProjectSetting;
use App\Repository\MethodRepository;
use Cycle\ORM\EntityManagerInterface;
use Nette\Utils\Random;

final readonly class ProjectService
{
    private const array DEFAULT_SETTINGS = [
        ['code' => 'return_url', 'value' => 'https://example.com/return', 'group' => 1],
        ['code' => 'color', 'value' => 'blue', 'group' => 2],
    ];

    public function __construct(
        private ProjectCodeGenerator $codeGenerator,
        private EntityManagerInterface $entityManager,
        private MethodRepository $methodRepository,
    ) {}

    public function createWithDefaultMethods(string $userId, bool $isSandbox): Project
    {
        $project = new Project();
        $project->setUserId($userId);
        $project->setSecretKey(uniqid());
        $project->setHash(Random::generate(32));
        $project->setCode($this->codeGenerator->getName());
        $project->setIsSandbox((int)$isSandbox);

        $this->entityManager->persist($project)->run();

        foreach ($this->methodRepository->findAll() as $method) {
            $projectMethod = new ProjectMethod();
            $projectMethod->setProjectId($project->getId());
            $projectMethod->setMethodId($method->getId());
            $this->entityManager->persist($projectMethod);
        }

        foreach (self::DEFAULT_SETTINGS as $setting) {
            $projectSetting = new ProjectSetting();
            $projectSetting->setProjectId($project->getId());
            $projectSetting->setCode($setting['code']);
            $projectSetting->setValue($setting['value']);
            $projectSetting->setGroup($setting['group']);
            $this->entityManager->persist($projectSetting);
        }

        $this->entityManager->run();

        return $project;
    }
}
