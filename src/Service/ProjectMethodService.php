<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project\ProjectMethod;
use App\Repository\ProjectMethodRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class ProjectMethodService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectMethodRepository $projectMethodRepository,
    ) {}

    public function replaceMethods(int $projectId, array $methodIds): void
    {
        $this->projectMethodRepository->deleteProjectMethods($projectId);

        foreach ($methodIds as $methodId) {
            $projectMethod = new ProjectMethod();
            $projectMethod->setProjectId($projectId);
            $projectMethod->setMethodId($methodId);
            $this->entityManager->persist($projectMethod);
        }

        $this->entityManager->run();
    }
}
