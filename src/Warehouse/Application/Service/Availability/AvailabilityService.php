<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\Availability;

use Daamian\WarehouseAlgorithm\Warehouse\Application\Query\WarehouseStateQueryInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;

class AvailabilityService implements AvailabilityInterface
{
    private WarehouseStateQueryInterface $warehouseStateQuery;
    private WarehouseSelectorInterface $warehouseSelector;

    public function __construct(WarehouseStateQueryInterface $warehouseStateQuery, WarehouseSelectorInterface $warehouseSelector)
    {
        $this->warehouseStateQuery = $warehouseStateQuery;
        $this->warehouseSelector = $warehouseSelector;
    }

    public function blockItems(string $blockerId, Item ...$items): void
    {
        $warehouseStates = $this->warehouseStateQuery->getAllStates();
        $statesToBlock = $this->warehouseSelector->selectWarehouses($warehouseStates, $items);
    }
}