<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\Availability;

use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;

interface AvailabilityInterface
{
    /**
     * @throws NotEnoughQuantityOfResourceException
     */
    public function blockItems(string $blockerId, Item ...$items): void;

}