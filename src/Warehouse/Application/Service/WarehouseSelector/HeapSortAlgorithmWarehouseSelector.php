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
            $warehousesForResource = $itemsWarehouseMap[$resourceId];

            $qty = $item->getQuantity();

            if (!empty($selectedWarehouses)) {
                $this->searchInSelectedWarehouses($selectedWarehouses, $warehousesForResource, $resourceId, $qty);
            }

            if ($qty <= 0 || empty($warehousesForResource)) {
                continue;
            }

            $this->searchInWarehousesForResource($selectedWarehouses, $warehousesForResource, $resourceId, $qty);
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
        $selectedWarehousesTmp = array_keys($selectedWarehouses);

        $selectedWarehousesTmp = array_filter(
            array_keys($selectedWarehouses),
            function ($warehouseId) use ($warehousesForResource) {
                return isset($warehousesForResource[$warehouseId]);
            }
        );

        $end = false;
        $index = 0;
        $warehousesForResourceTmp = $warehousesForResource;

        while (false === $end) {
            usort($selectedWarehousesTmp, function ($a, $b) use ($warehousesForResourceTmp, $qty) {
                $a = $warehousesForResourceTmp[$a];
                $b = $warehousesForResourceTmp[$b];

                if ($a['quantity'] >= $qty && $b['quantity'] >= $qty) {
                    return ($a['priority'] > $b['priority']) ? 1 : -1;
                } else {
                    return ($a['quantity'] < $b['quantity']) ? 1 : -1;
                }
            });

            $warehouseId = $selectedWarehousesTmp[$index];

            //TODO fix
            /*if (!isset($warehousesForResource[$selectedWarehouse['warehouseId']])) {
                unset($selectedWarehousesTmp[$index]);
                if (empty($selectedWarehousesTmp)) {
                    $end = true;
                }
                continue;
            }*/

            $warehouse = $warehousesForResource[$warehouseId];

            if ($warehouse['quantity'] >= $qty) {
                $selectedWarehouses[$warehouse['warehouseId']][] = [
                    'resourceId' => $resourceId,
                    'quantity' => $qty
                ];
                $qty -= $warehouse['quantity'];
                $end = true;
            } else {
                $selectedWarehouses[$warehouse['warehouseId']][] = [
                    'resourceId' => $resourceId,
                    'quantity' => $warehouse['quantity']
                ];
                $qty -= $warehouse['quantity'];
            }

            unset($warehousesForResource[$warehouseId]);
            unset($selectedWarehousesTmp[$index]);

            if (empty($selectedWarehousesTmp)) {
                $end = true;
            }

        }

        return $selectedWarehouses;
    }

    private function searchInWarehousesForResource(array &$selectedWarehouses, array $warehousesForResource, string $resourceId, int $qty): void
    {
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
                $selectedWarehouses[$warehouse['warehouseId']][] = [
                    'resourceId' => $resourceId,
                    'quantity' => $qty
                ];
                $qty -= $warehouse['quantity'];
                $end = true;
            } else {
                $selectedWarehouses[$warehouse['warehouseId']][] = [
                    'resourceId' => $resourceId,
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
}