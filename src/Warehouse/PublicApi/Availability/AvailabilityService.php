<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\PublicApi\Availability;

use Daamian\WarehouseAlgorithm\Warehouse\Application\Query\WarehouseStateQueryInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker\DTO\Item as StateBlockerItem;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateBlocker\StateBlockerInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateChecker\StateCheckerInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\DTO\Item as WarehouseItem;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\DTO\Items;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\WarehouseSelectorInterface;
use Daamian\WarehouseAlgorithm\Warehouse\PublicApi\Availability\DTO\Item;

class AvailabilityService implements AvailabilityInterface
{
    private WarehouseStateQueryInterface $warehouseStateQuery;
    private WarehouseSelectorInterface $warehouseSelector;
    private StateBlockerInterface $blocker;
    private StateCheckerInterface $stateChecker;

    public function __construct(
        WarehouseStateQueryInterface $warehouseStateQuery,
        WarehouseSelectorInterface   $warehouseSelector,
        StateBlockerInterface        $blocker,
        StateCheckerInterface        $stateChecker
    )
    {
        $this->warehouseStateQuery = $warehouseStateQuery;
        $this->warehouseSelector = $warehouseSelector;
        $this->blocker = $blocker;
        $this->stateChecker = $stateChecker;
    }

    public function blockItems(string $blockerId, Item ...$items): void
    {
        foreach ($items as $item) {
            if (!$this->stateChecker->isAvailable($item->getResourceId(), $item->getQuantity())) {
                throw new NotEnoughQuantityOfResourceException(sprintf("Not enough quantity of resource %s to block", $item->getResourceId()));
            }
        }

        $warehousesSelected = $this->warehouseSelector->selectWarehouses(
            $this->warehouseStateQuery->getAllStates(),
            new Items(...array_map(function (Item $item) {
                return new WarehouseItem($item->getResourceId(), $item->getQuantity());
            }, $items))
        );

        $this->blocker->blockStates(...array_map(function (array $warehouseSelected) use ($blockerId) {
            return new StateBlockerItem($warehouseSelected['resourceId'], $warehouseSelected['warehouseId'], $blockerId, $warehouseSelected['quantity']);
        }, $warehousesSelected));
    }

}