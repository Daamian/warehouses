<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker\DTO;

class Item
{
    private string $resourceId;
    private string $warehouseId;
    private string $blockerId;
    private int $quantity;

    public function __construct(string $resourceId, string $warehouseId, string $blockerId, int $quantity)
    {
        $this->resourceId = $resourceId;
        $this->warehouseId = $warehouseId;
        $this->blockerId = $blockerId;
        $this->quantity = $quantity;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getWarehouseId(): string
    {
        return $this->warehouseId;
    }

    public function getBlockerId(): string
    {
        return $this->blockerId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}