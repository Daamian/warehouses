<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker;

use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker\DTO\Item;

interface StateBlockerInterface
{
    public function blockStates(Item ...$itemsToBlock): void;
}