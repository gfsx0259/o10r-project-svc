<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Gateway\Scenario;
use App\Repository\ScenarioRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class GatewayScenarioService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ScenarioRepository $scenarioRepository,
    ) {}

    public function persist(array $payload): Scenario
    {
        $scenario = isset($payload['id'])
            ? $this->scenarioRepository->findByPK($payload['id'])
            : new Scenario();

        $scenario->setTitle($payload['title']);
        $scenario->setRouteId($payload['route_id']);
        $scenario->setConditions($payload['conditions']);

        $this->entityManager->persist($scenario)->run();

        return $scenario;
    }

    public function delete(int $id): void
    {
        $scenario = $this->scenarioRepository->findByPK($id);
        $this->entityManager->delete($scenario)->run();
    }
}
