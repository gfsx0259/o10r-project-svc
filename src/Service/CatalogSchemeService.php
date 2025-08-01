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

    public function create(array $payload): MethodFormSchema
    {
        $scheme = new MethodFormSchema();
        $scheme->setMethodId($payload['method_id']);
        $scheme->setFields($payload['fields']);

        $this->entityManager->persist($scheme);
        $this->entityManager->run();

        return $scheme;
    }

    public function update(int $id, array $payload): MethodFormSchema
    {
        $scheme = $this->methodFormSchemaRepository->findByPK($id);

        $scheme->setMethodId($payload['method_id']);
        $scheme->setFields($payload['fields']);

        $this->entityManager->persist($scheme);
        $this->entityManager->run();

        return $scheme;
    }
}
