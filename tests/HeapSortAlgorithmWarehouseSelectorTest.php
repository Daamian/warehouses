<?php

namespace Daamian\WarehouseAlgorithm\Tests;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\StateItem;
use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\HeapSortAlgorithmWarehouseSelector;
use PHPUnit\Framework\TestCase;

class HeapSortAlgorithmWarehouseSelectorTest extends TestCase
{
    private HeapSortAlgorithmWarehouseSelector $warehouseSelector;

    public function setUp(): void
    {
        $this->warehouseSelector = new HeapSortAlgorithmWarehouseSelector();
    }

    public function testCoffeFilterSelectWarehouses(): void
    {
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-main', 1, new StateItem('resource-1', 5)),
            new WarehouseState('warehouse-katowice', 2, new StateItem('resource-1', 4)),
            new WarehouseState('warehouse-gdansk', 3, new StateItem('resource-1', 3))
        ];

        $itemsToSelect = [
            new Item('resource-1', 9)
        ];

        //Expected
        $warehousesSelectedExpected = [
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-main', 'quantity' => 5],
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-katowice', 'quantity' => 4]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

    public function testComplexSelectWarehouses(): void
    {
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-main', 1, new StateItem('resource-1', 5), new StateItem('resource-2', 1), new StateItem('resource-3', 5)),
            new WarehouseState('warehouse-katowice', 2, new StateItem('resource-1', 4), new StateItem('resource-2', 15), new StateItem('resource-3', 2)),
            new WarehouseState('warehouse-gdansk', 3, new StateItem('resource-1', 1), new StateItem('resource-3', 20))
        ];


        $itemsToSelect = [
            new Item('resource-1', 8),
            new Item('resource-3', 6)
        ];

        //Expected
        $warehousesSelectedExpected = [
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-main', 'quantity' => 5],
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-katowice', 'quantity' => 3],
            ['resourceId' => 'resource-3', 'warehouseId' => 'warehouse-main', 'quantity' => 5],
            ['resourceId' => 'resource-3', 'warehouseId' => 'warehouse-katowice', 'quantity' => 1]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

    public function testComplex2SelectWarehouses(): void
    {
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-main', 1, new StateItem('resource-1', 5), new StateItem('resource-2', 1), new StateItem('resource-3', 5)),
            new WarehouseState('warehouse-katowice', 2, new StateItem('resource-1', 4), new StateItem('resource-2', 15), new StateItem('resource-3', 2)),
            new WarehouseState('warehouse-gdansk', 3, new StateItem('resource-1', 1), new StateItem('resource-2', 2), new StateItem('resource-3', 20)),
            new WarehouseState('warehouse-gliwice', 4, new StateItem('resource-1', 100), new StateItem('resource-2', 2), new StateItem('resource-3', 100))
        ];


        $itemsToSelect = [
            new Item('resource-1', 8),
            new Item('resource-3', 6)
        ];

        //Expected
        $warehousesSelectedExpected = [
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-gliwice', 'quantity' => 8],
            ['resourceId' => 'resource-3', 'warehouseId' => 'warehouse-gliwice', 'quantity' => 6],
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

    //From merce

    public function testWithOnePossibleResult(): void
    {
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-A', 1, new StateItem('resource-1', 2), new StateItem('resource-2', 5)),
            new WarehouseState('warehouse-B', 2, new StateItem('resource-1', 1), new StateItem('resource-2', 4), new StateItem('resource-3', 4)),
            new WarehouseState('warehouse-C', 3, new StateItem('resource-1', 3), new StateItem('resource-2', 1), new StateItem('resource-3', 4))
        ];

        $itemsToSelect = [
            new Item('resource-1', 5),
            new Item('resource-2', 4),
            new Item('resource-3', 1)
        ];

        //Expected
        $warehousesSelectedExpected = [
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-C', 'quantity' => 3],
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-A', 'quantity' => 2],
            ['resourceId' => 'resource-2', 'warehouseId' => 'warehouse-A', 'quantity' => 4],
            ['resourceId' => 'resource-3', 'warehouseId' => 'warehouse-C', 'quantity' => 1]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }


    public function testWithPriorityForTwoPossibleResult(): void
    {
        //TODO
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-A', 1, new StateItem('resource-2', 1), new StateItem('resource-3', 1)),
            new WarehouseState('warehouse-B', 2, new StateItem('resource-1', 2), new StateItem('resource-2', 2), new StateItem('resource-3', 2)),
            new WarehouseState('warehouse-C', 3, new StateItem('resource-1', 3), new StateItem('resource-2', 3), new StateItem('resource-3', 3)),
            new WarehouseState('warehouse-D', 4, new StateItem('resource-1', 4), new StateItem('resource-2', 4), new StateItem('resource-3', 4)),
            new WarehouseState('warehouse-E', 5, new StateItem('resource-1', 5), new StateItem('resource-2', 5), new StateItem('resource-3', 5))
        ];

        $itemsToSelect = [
            new Item('resource-1', 8),
            new Item('resource-2', 8),
            new Item('resource-3', 8)
        ];

        //Expected
        $warehousesSelectedExpected = [
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-E', 'quantity' => 5],
            ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-C', 'quantity' => 3],
            ['resourceId' => 'resource-2', 'warehouseId' => 'warehouse-E', 'quantity' => 5],
            ['resourceId' => 'resource-2', 'warehouseId' => 'warehouse-C', 'quantity' => 3],
            ['resourceId' => 'resource-3', 'warehouseId' => 'warehouse-E', 'quantity' => 5],
            ['resourceId' => 'resource-3', 'warehouseId' => 'warehouse-C', 'quantity' => 3]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

    public function testWithoutPossibleResultForBigData(): void
    {
        //Given
        $warehouseStates = [];

        $states = [];
        for ($j = 1; $j <= 100; $j++) {
            $states[] = new StateItem('resource-' . $j, 1);
        }

        for ($i = 1; $i <= 100; $i++) {
            $warehouseStates[] = new WarehouseState('warehouse-' . $i, $i, ...$states);
        }

        $itemsToSelect = [
            new Item('resource-1', 100),
            new Item('resource-50', 100),
            new Item('resource-99', 100)
        ];

        //Expected
        $warehousesSelectedExpected = [];

        for ($i = 1; $i <= 100; $i++) {
            $warehousesSelectedExpected[] = ['resourceId' => 'resource-1', 'warehouseId' => 'warehouse-' . $i, 'quantity' => 1];
        }

        for ($i = 1; $i <= 100; $i++) {
            $warehousesSelectedExpected[] = ['resourceId' => 'resource-50', 'warehouseId' => 'warehouse-' . $i, 'quantity' => 1];
        }

        for ($i = 1; $i <= 100; $i++) {
            $warehousesSelectedExpected[] = ['resourceId' => 'resource-99', 'warehouseId' => 'warehouse-' . $i, 'quantity' => 1];
        }

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        //$this->assertTrue(true);
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

    public function testWithOnePossibleResultBigData(): void
    {
        //Given
        $warehouseStates = [];
        for ($i = 1; $i <= 98; $i++) {
            $warehouseStates[] = new WarehouseState('warehouse-' . $i, $i + 2, ...[new StateItem('resource-0', rand(1, 13))]);
        }

        $warehouseStates[] = new WarehouseState('warehouse-99', 1, new StateItem('resource-0', 22));
        $warehouseStates[] = new WarehouseState('warehouse-100', 2, new StateItem('resource-0', 30));

        $itemsToSelect = [
            new Item('resource-0', 15)
        ];

        //Expected
        $warehousesSelectedExpected = [['resourceId' => 'resource-0', 'warehouseId' => 'warehouse-99', 'quantity' => 15]];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses($warehouseStates, $itemsToSelect);

        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

}