<?php

namespace App\Module\Dummy;

use App\Module\Dummy\Collection\ArrayCollection;

final class State
{
    /**
     * @var string Unique payment id provided by user
     */
    private string $paymentId;

    /**
     * @var int Route identity detected at sale request
     */
    private int $routeId;

    /**
     * @var array Initial request body
     */
    private array $initialRequest;

    /**
     * @var int Indicates position of next available collection callback
     */
    private int $cursor = 0;

    private array $actions = [];

    /**
     * @param string $paymentId
     * @param int $routeId
     * @param array $initialRequest
     */
    public function __construct(string $paymentId, int $routeId, array $initialRequest)
    {
        $this->paymentId = $paymentId;
        $this->routeId = $routeId;
        $this->initialRequest = $initialRequest;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getRouteId(): int
    {
        return $this->routeId;
    }

    public function getInitialRequest(): ArrayCollection
    {
        return new ArrayCollection($this->initialRequest);
    }

    public function getCursor(): int
    {
        return $this->cursor;
    }

    public function next(): void
    {
        $this->cursor += 1;
    }

    public function registerAction(string $key): self
    {
        if (!isset($this->actions[$key])) {
            $this->actions[$key] = false;
        }

        return $this;
    }

    public function completeAction(string $key): self
    {
        $this->actions[$key] = true;

        return $this;
    }

    public function isActionCompleted(string $key): bool
    {
        return $this->actions[$key] ?? false === true;
    }

    public function isActionRegistered(string $key): bool
    {
        return isset($this->actions[$key]);
    }
}
