<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Catalog\Method;
use App\Entity\Catalog\MethodFormSchema;
use App\Entity\Gateway\Callback;
use App\Entity\Gateway\Route;
use App\Entity\Gateway\Scenario;
use App\Entity\Gateway\StaticRoute;
use App\Fixture\Catalog\MethodDto;
use App\Fixture\Catalog\SchemaDto;
use App\Fixture\Gateway\ResourceDto;
use App\Fixture\Gateway\RouteDto;
use App\Repository\MethodRepository;
use Cycle\ORM\EntityManagerInterface;
use Safe\Exceptions\FilesystemException;
use Throwable;
use Yiisoft\Aliases\Aliases;

final readonly class FixtureLoader
{
    /**
     * @param RouteDto[] $routeDefinitions
     * @param ResourceDto[] $resourceDefinitions
     * @param MethodDto[] $methodDefinitions
     * @param SchemaDto[] $schemaDefinitions
     * @param EntityManagerInterface $entityManager
     * @param MethodRepository $methodRepository
     * @param Aliases $aliases
     */
    public function __construct(
        private array $routeDefinitions,
        private array $resourceDefinitions,
        private array $methodDefinitions,
        private array $schemaDefinitions,
        private EntityManagerInterface $entityManager,
        private MethodRepository $methodRepository,
        private Aliases $aliases,
    ) {}

    /**
     * @throws Throwable
     */
    public function load(): void
    {
        $this->saveEntities(
            array_map(fn (MethodDto $methodDto) => $this->buildMethod($methodDto), $this->methodDefinitions)
        );

        $this->entityManager->run();

        $this->saveEntities(
            array_map(fn (SchemaDto $schemaDto) => $this->buildSchema($schemaDto), $this->schemaDefinitions)
        );

        $this->saveEntities(
            array_map(fn (RouteDto $routeDto) => $this->buildRoute($routeDto), $this->routeDefinitions)
        );

        $this->saveEntities(
            array_map(fn (ResourceDto $resourceDto) => $this->buildResource($resourceDto), $this->resourceDefinitions)
        );

        $this->entityManager->run();
    }


    private function buildMethod(MethodDto $methodDto): Method
    {
        $method = new Method();

        $method->setCode($methodDto->code);
        $method->setTitle($methodDto->title);
        $method->setDescription($methodDto->description);

        return $method;
    }

    private function buildSchema(SchemaDto $schemaDto): MethodFormSchema
    {
        $methodSchema = new MethodFormSchema();

        $methodSchema->setMethodId($this->methodRepository->getByCode($schemaDto->methodCode)->getId());
        $methodSchema->setFields($this->readFile($schemaDto->contentPath));

        return $methodSchema;
    }

    private function buildRoute(RouteDto $routeDto): Route
    {
        $route = new Route();
        $route->setMethodId($this->methodRepository->getByCode($routeDto->methodCode)->getId());

        foreach ($routeDto->scenarios as $scenarioDto) {
            $scenario = new Scenario();
            $scenario->setTitle($scenarioDto->title);

            foreach ($scenarioDto->callbacks as $index => $callbackPath) {
                $callback = new Callback();
                $callback->setBody($this->readFile($callbackPath));
                $callback->setOrder($index);

                $scenario->addCallback($callback);
            }

            $route->addScenario($scenario);
        }

        return $route;
    }

    private function buildResource(ResourceDto $resourceDto): StaticRoute
    {
        $resource = new StaticRoute();

        $resource->setName($resourceDto->name);
        $resource->setPath($resourceDto->path);
        $resource->setBody($this->readFile($resourceDto->contentPath));

        return $resource;
    }

    /**
     * @throws Throwable
     */
    private function saveEntities($objects): void
    {
        foreach ($objects as $object) {
            $this->entityManager->persist($object);
        }
    }

    /**
     * @throws FilesystemException
     */
    private function readFile(string $path): string
    {
        return \Safe\file_get_contents($this->aliases->get('@fixture/' . $path));
    }
}
