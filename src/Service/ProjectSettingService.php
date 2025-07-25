<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProjectSetting;
use App\Repository\ProjectSettingRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class ProjectSettingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectSettingRepository $projectSettingRepository,
    ) {}

    public function updateSettings(int $projectId, array $settings): void
    {
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

        $this->entityManager->run();
    }
}
