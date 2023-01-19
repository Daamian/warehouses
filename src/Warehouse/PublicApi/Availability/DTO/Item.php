<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\PublicApi\Availability\DTO;

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