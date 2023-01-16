<?php

namespace Daamian\WarehouseAlgorithm\Shared;

abstract class AggregateRoot
{
    private array $events = [];

    public function registerEvent(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}