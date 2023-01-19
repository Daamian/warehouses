<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Query;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\States;
use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;

interface WarehouseStateQueryInterface
{
    public function getAllStates(): States;
}