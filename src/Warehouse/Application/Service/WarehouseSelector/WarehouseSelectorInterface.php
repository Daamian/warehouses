<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\States;
use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Items;
use JetBrains\PhpStorm\ArrayShape;

interface WarehouseSelectorInterface
{
    #[ArrayShape([
        'string' /* Key of this array is warehouseId */ => [
            'resourceId' => 'string',
            'quantity' => 'int'
        ]
    ])]
    public function selectWarehouses(States $warehouseStates, Items $itemsToSelect): array;
}