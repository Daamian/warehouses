<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\Availability;

use Daamian\WarehouseAlgorithm\Warehouse\Application\Query\WarehouseStateQueryInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker\StateBlockerInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\WarehouseSelectorInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker\DTO\Item as StateBlockerItem;

class AvailabilityService implements AvailabilityInterface
{
    private WarehouseStateQueryInterface $warehouseStateQuery;
    private WarehouseSelectorInterface $warehouseSelector;
    private StateBlockerInterface $blocker;

    public function __construct(WarehouseStateQueryInterface $warehouseStateQuery, WarehouseSelectorInterface $warehouseSelector, StateBlockerInterface $blocker)
    {
        $this->warehouseStateQuery = $warehouseStateQuery;
        $this->warehouseSelector = $warehouseSelector;
        $this->blocker = $blocker;
    }

    public function blockItems(string $blockerId, Item ...$items): void
    {
        $warehouseStates = $this->warehouseStateQuery->getAllStates();
        $warehousesSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $items);
        $this->blocker->blockStates(...array_map(function (array $warehouseSelected) use ($blockerId) {
            return new StateBlockerItem($warehouseSelected['resourceId'], $warehouseSelected['warehouseId'], $blockerId, $warehouseSelected['quantity']);
        }, $warehousesSelected));
    }
}