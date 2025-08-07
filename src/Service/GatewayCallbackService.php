<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Gateway\Callback;
use App\Repository\CallbackRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class GatewayCallbackService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CallbackRepository $callbackRepository,
    ) {}

    public function persist(array $payload): Callback
    {
        $callback = isset($payload['id'])
            ? $this->callbackRepository->findByPK($payload['id'])
            : new Callback();

        $callback->setBody($payload['body']);
        $callback->setScenarioId($payload['scenario_id']);
        $callback->setOrder($payload['order']);

        $this->entityManager->persist($callback)->run();

        return $callback;
    }

    public function delete(int $id): void
    {
        $callback = $this->callbackRepository->findByPK($id);
        $this->entityManager->delete($callback)->run();
    }
}
