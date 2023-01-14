<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Query;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;

interface WarehouseStateQueryInterface
{
    /**
     * @return WarehouseState[]
     */
    public function getAllStates(): array;
}