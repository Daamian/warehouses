<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\Availability;

use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Items;

interface AvailabilityInterface
{
    /**
     * @throws NotEnoughQuantityOfResourceException
     */
    public function blockItems(string $blockerId, Items $items): void;

}