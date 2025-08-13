<?php

namespace App\Module\Dummy;

use App\Module\Dummy\Action\AbstractAction;
use App\Module\Dummy\Action\AcsAction;
use App\Module\Dummy\Action\ApsAction;
use App\Module\Dummy\Collection\ArrayCollection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Yiisoft\Injector\Injector;

readonly class ActionFactory
{
    public function __construct(
        private Injector $injector,
        private LoggerInterface $logger
    ) {}

    public function make(ArrayCollection $callback, State $state): ?AbstractAction
    {
        $this->logger->info('Try to create action', [
            'callback' => $callback,
            'state' => $state,
        ]);

        try {
            if ($callback->get('return_url.url')) {
                return $this->injector->make(ApsAction::class, [$callback, $state]);
            }
            if ($callback->get('acs.acs_url')) {
                return $this->injector->make(AcsAction::class, [$callback, $state]);
            }
        } catch (ReflectionException | ContainerExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());
        }

        return null;
    }
}
