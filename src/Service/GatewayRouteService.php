<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Gateway\Route;
use App\Repository\RouteRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class GatewayRouteService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RouteRepository $routeRepository,
    ) {}

    public function persist(array $payload): Route
    {
        $route = isset($payload['id'])
            ? $this->routeRepository->findByPK($payload['id'])
            : new Route();

        $route->setMethodId($payload['method_id']);
        $route->setConditions($payload['conditions']);

        $this->entityManager->persist($route)->run();

        return $route;
    }

    public function delete(int $id): void
    {
        $route = $this->routeRepository->findByPK($id);
        $this->entityManager->delete($route)->run();
    }
}
