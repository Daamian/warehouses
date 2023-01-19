<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\PublicApi\Availability;

use Daamian\WarehouseAlgorithm\Warehouse\PublicApi\Availability\DTO\Item;

interface AvailabilityInterface
{
    /**
     * @throws NotEnoughQuantityOfResourceException
     */
    public function blockItems(string $blockerId, Item ...$items): void;

}