<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel;

final class WarehouseState
{
    private string $warehouseId;

    /**
     * @var StateItem[]
     */
    private array $states;

    public function __construct(string $warehouseId, StateItem ...$states)
    {
        $this->warehouseId = $warehouseId;
        $this->states = $states;
    }

    public function getWarehouseId(): string
    {
        return $this->warehouseId;
    }

    /**
     * @return StateItem[]
     */
    public function getStates(): array
    {
        return $this->states;
    }
}