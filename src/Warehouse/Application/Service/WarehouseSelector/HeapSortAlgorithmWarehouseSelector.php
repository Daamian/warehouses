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
            $warehousesForResource = $itemsWarehouseMap[$resourceId];

            $qty = $quantity;

            if (!empty($selectedWarehouses)) {
                $this->searchInSelectedWarehouses($selectedWarehouses, $warehousesForResource, $resourceId, $qty);
            }

            if ($qty <= 0 || empty($warehousesForResource)) {
                continue;
            }

            $end = false;
            $index = 0;
            while (false === $end) {
                usort($warehousesForResource, function ($a, $b) use ($qty) {
                    if ($a['quantity'] >= $qty && $b['quantity'] >= $qty) {
                        return ($a['priority'] > $b['priority']) ? 1 : -1;
                    } else {
                        return ($a['quantity'] < $b['quantity']) ? 1 : -1;
                    }
                });

                $warehouse = $warehousesForResource[$index];

                if ($warehouse['quantity'] >= $qty) {
                    $selectedWarehouses[] = [
                        'resourceId' => $resourceId,
                        'warehouseId' => $warehouse['warehouseId'],
                        'quantity' => $qty
                    ];
                    $qty -= $warehouse['quantity'];
                    $end = true;
                } else {
                    $selectedWarehouses[] = [
                        'resourceId' => $resourceId,
                        'warehouseId' => $warehouse['warehouseId'],
                        'quantity' => $warehouse['quantity']
                    ];
                    $qty -= $warehouse['quantity'];
                }

                unset($warehousesForResource[$index]);

                if (empty($warehousesForResource)) {
                    $end = true;
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
                $itemsWarehouseMap[$stateItem->getId()][$warehouseState->getWarehouseId()] = [
                    'warehouseId' => $warehouseState->getWarehouseId(),
                    'quantity' => $stateItem->getQuantity(),
                    'priority' => $warehouseState->getPriority()
                ];
            }
        }

        return $itemsWarehouseMap;
    }

    private function searchInSelectedWarehouses(array &$selectedWarehouses, array &$warehousesForResource, string $resourceId, int &$qty): array
    {
        $selectedWarehousesTmp = $selectedWarehouses;
        $selectedWarehousesTmp = array_filter(
            $selectedWarehousesTmp,
            function ($warehouse) use ($warehousesForResource) {
                return isset($warehousesForResource[$warehouse['warehouseId']]);
            }
        );

        $end = false;
        $index = 0;
        $warehousesForResourceTmp = $warehousesForResource;
        while (false === $end) {
            usort($selectedWarehousesTmp, function ($a, $b) use ($warehousesForResourceTmp, $qty) {
                $a = $warehousesForResourceTmp[$a['warehouseId']];
                $b = $warehousesForResourceTmp[$b['warehouseId']];

                if ($a['quantity'] >= $qty && $b['quantity'] >= $qty) {
                    return ($a['priority'] > $b['priority']) ? 1 : -1;
                } else {
                    return ($a['quantity'] < $b['quantity']) ? 1 : -1;
                }
            });

            $selectedWarehouse = $selectedWarehousesTmp[$index];

            if (!isset($warehousesForResource[$selectedWarehouse['warehouseId']])) {
                unset($selectedWarehousesTmp[$index]);
                if (empty($selectedWarehousesTmp)) {
                    $end = true;
                }
                continue;
            }

            $warehouse = $warehousesForResource[$selectedWarehouse['warehouseId']];

            if ($warehouse['quantity'] >= $qty) {
                $selectedWarehouses[] = [
                    'resourceId' => $resourceId,
                    'warehouseId' => $warehouse['warehouseId'],
                    'quantity' => $qty
                ];
                $qty -= $warehouse['quantity'];
                $end = true;
            } else {
                $selectedWarehouses[] = [
                    'resourceId' => $resourceId,
                    'warehouseId' => $warehouse['warehouseId'],
                    'quantity' => $warehouse['quantity']
                ];
                $qty -= $warehouse['quantity'];
            }

            unset($warehousesForResource[$selectedWarehouse['warehouseId']]);
            unset($selectedWarehousesTmp[$index]);

            if (empty($selectedWarehousesTmp)) {
                $end = true;
            }

        }

        return $selectedWarehouses;
    }
}