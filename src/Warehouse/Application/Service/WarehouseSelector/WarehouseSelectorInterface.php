<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;

interface WarehouseSelectorInterface
{
    /**
     * @param WarehouseState[] $warehouseStates
     * @param Item[] $itemsToSelect
     * @return array
     */
    public function selectWarehouses(array $warehouseStates, array $itemsToSelect): array;
}