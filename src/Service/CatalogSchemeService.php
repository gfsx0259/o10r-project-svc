<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MethodFormSchema;
use App\Repository\MethodFormSchemaRepository;
use Cycle\ORM\EntityManagerInterface;

final readonly class CatalogSchemeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MethodFormSchemaRepository $methodFormSchemaRepository,
    ) {}

    public function persist(array $payload): MethodFormSchema
    {
        $scheme = isset($payload['id'])
            ? $this->methodFormSchemaRepository->findByPK($payload['id'])
            : new MethodFormSchema();

        $scheme->setMethodId($payload['method_id']);
        $scheme->setFields($payload['fields']);

        $this->entityManager->persist($scheme)->run();

        return $scheme;
    }

    public function delete(int $id): void
    {
        $scheme = $this->methodFormSchemaRepository->findByPK($id);
        $this->entityManager->delete($scheme)->run();
    }
}
