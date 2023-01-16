<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel;

final class WarehouseState
{
    private string $warehouseId;
    private int $priority;

    /**
     * @var StateItem[]
     */
    private array $states;

    public function __construct(string $warehouseId, int $priority, StateItem ...$states)
    {
        $this->warehouseId = $warehouseId;
        $this->priority = $priority;
        $this->states = $states;
    }

    public function getWarehouseId(): string
    {
        return $this->warehouseId;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return StateItem[]
     */
    public function getStates(): array
    {
        return $this->states;
    }
}