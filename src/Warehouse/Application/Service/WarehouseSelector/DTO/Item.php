<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\DTO;

class Item
{
    private string $resourceId;
    private int $quantity;

    public function __construct(string $resourceId, int $quantity)
    {
        $this->resourceId = $resourceId;
        $this->quantity = $quantity;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}