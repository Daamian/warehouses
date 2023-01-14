<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel;

class StateItem
{
    private string $id;
    private int $quantity;

    public function __construct(string $id, int $quantity)
    {
        $this->id = $id;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}