<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Catalog\Method;
use App\Repository\MethodRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class CatalogMethodService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MethodRepository $methodRepository,
    ) {}

    public function persist(array $payload): Method
    {
        $method = isset($payload['id'])
            ? $this->methodRepository->findByPK($payload['id'])
            : new Method();

        $method->setTitle($payload['title']);
        $method->setCode($payload['code']);
        $method->setDescription($payload['description']);

        $this->entityManager->persist($method)->run();

        return $method;
    }

    public function delete(int $id): void
    {
        $method = $this->methodRepository->findByPK($id);
        $this->entityManager->delete($method)->run();
    }
}
