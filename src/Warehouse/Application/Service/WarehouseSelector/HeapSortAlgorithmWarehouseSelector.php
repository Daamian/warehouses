<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\StateItem;
use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;

class HeapSortAlgorithmWarehouseSelector implements WarehouseSelectorInterface
{

    /**
     * @param WarehouseState[] $warehouseStates
     * @param Item[] $itemsToSelect
     */
    public function selectWarehouses(array $warehouseStates, array $itemsToSelect): array
    {
        $itemsWarehouseMap = $this->createItemsWarehouseMap($warehouseStates);

        $selectedWarehouses = array();
        foreach ($itemsToSelect as $item) {
            $resourceId = $item->getResourceId();
            $quantity = $item->getQuantity();
            $warehousesForProduct = $itemsWarehouseMap[$resourceId];
            usort($warehousesForProduct, function ($a, $b) {
                return $a['quantity'] < $b['quantity'];
            });
            $qty = $quantity;
            foreach ($warehousesForProduct as $warehouse) {
                if ($warehouse['quantity'] >= $qty) {
                    $selectedWarehouses[] = [
                        'resourceId' => $resourceId,
                        'warehouseId' => $warehouse['warehouseId'],
                        'quantity' => $qty
                    ];
                    break;
                } else {
                    $selectedWarehouses[] = [
                        'resourceId' => $resourceId,
                        'warehouseId' => $warehouse['warehouseId'],
                        'quantity' => $warehouse['quantity']
                    ];
                    $qty -= $warehouse['quantity'];
                }
            }
        }

        return $selectedWarehouses;
    }

    /**
     * @param WarehouseState[] $warehouseStates
     */
    private function createItemsWarehouseMap(array $warehouseStates): array
    {
        $itemsWarehouseMap = [];
        foreach ($warehouseStates as $warehouseState) {
            foreach ($warehouseState->getStates() as $stateItem) {
                if (!isset($itemsWarehouseMap[$stateItem->getId()])) {
                    $itemsWarehouseMap[$stateItem->getId()] = [];
                }
                $itemsWarehouseMap[$stateItem->getId()][] = ['warehouseId' => $warehouseState->getWarehouseId(), 'quantity' => $stateItem->getQuantity()];
            }
        }

        return $itemsWarehouseMap;
    }
}