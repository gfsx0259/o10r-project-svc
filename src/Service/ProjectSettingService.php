<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project\ProjectSetting;
use App\Repository\ProjectRepository;
use App\Repository\ProjectSettingRepository;
use Cycle\ORM\EntityManagerInterface;
use Nette\Utils\Random;

final readonly class ProjectSettingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectSettingRepository $projectSettingRepository,
        private ProjectRepository $projectRepository,
    ) {}

    public function updateSettings(int $projectId, array $settings): void
    {
        $project = $this->projectRepository->getById($projectId);

        $existedSettings = $this->projectSettingRepository->findByProjectId($projectId);

        foreach ($settings as $setting) {
            $code = $setting['code'];
            $value = $setting['value'];
            $group = $setting['group'];

            $existedSetting = array_filter($existedSettings, fn (ProjectSetting $projectSetting) => $projectSetting->getCode() === $code)[0] ?? false;

            if ($existedSetting) {
                $existedSetting->setValue($value);
                $this->entityManager->persist($existedSetting);
            } else {
                $newSetting = new ProjectSetting();
                $newSetting->setProjectId($projectId);
                $newSetting->setCode($code);
                $newSetting->setValue($value);
                $newSetting->setGroup(array_flip(ProjectSettingRepository::SETTING_GROUP_CODE_MAP)[$group]);
                $this->entityManager->persist($newSetting);
            }
        }

        $project->setHash(Random::generate(32));
        $this->entityManager->persist($project)->run();
    }
}
