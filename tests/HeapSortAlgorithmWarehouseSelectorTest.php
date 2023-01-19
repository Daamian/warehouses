<?php

namespace Daamian\WarehouseAlgorithm\Tests;

use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\StateItem;
use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\States;
use Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel\WarehouseState;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\DTO\Item;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\DTO\Items;
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
            'warehouse-main' => [['resourceId' => 'resource-1', 'quantity' => 5]],
            'warehouse-katowice' => [['resourceId' => 'resource-1', 'quantity' => 4]]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

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
            'warehouse-main' => [['resourceId' => 'resource-1', 'quantity' => 5], ['resourceId' => 'resource-3', 'quantity' => 5]],
            'warehouse-katowice' => [['resourceId' => 'resource-1', 'quantity' => 3], ['resourceId' => 'resource-3', 'quantity' => 1]]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

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
            'warehouse-gliwice' => [['resourceId' => 'resource-1', 'quantity' => 8], ['resourceId' => 'resource-3', 'quantity' => 6]]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

    //From merce

    public function testWithOnePossibleResult(): void
    {
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-A', 10, new StateItem('resource-1', 2), new StateItem('resource-2', 5)),
            new WarehouseState('warehouse-B', 2, new StateItem('resource-1', 1), new StateItem('resource-2', 4), new StateItem('resource-3', 4)),
            new WarehouseState('warehouse-C', 20, new StateItem('resource-1', 3), new StateItem('resource-2', 1), new StateItem('resource-3', 4))
        ];

        $itemsToSelect = [
            new Item('resource-1', 5),
            new Item('resource-2', 4),
            new Item('resource-3', 1)
        ];

        //Expected
        $warehousesSelectedExpected = [
            'warehouse-C' => [['resourceId' => 'resource-1', 'quantity' => 3], ['resourceId' => 'resource-3', 'quantity' => 1]],
            'warehouse-A' => [['resourceId' => 'resource-1', 'quantity' => 2], ['resourceId' => 'resource-2', 'quantity' => 4]]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

        //Then
        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }


    public function testWithPriorityForTwoPossibleResult(): void
    {
        //TODO
        //Given
        $warehouseStates = [
            new WarehouseState('warehouse-A', 10, new StateItem('resource-2', 1), new StateItem('resource-3', 1)),
            new WarehouseState('warehouse-B', 2, new StateItem('resource-1', 2), new StateItem('resource-2', 2), new StateItem('resource-3', 2)),
            new WarehouseState('warehouse-C', 20, new StateItem('resource-1', 3), new StateItem('resource-2', 3), new StateItem('resource-3', 3)),
            new WarehouseState('warehouse-D', 25, new StateItem('resource-1', 4), new StateItem('resource-2', 4), new StateItem('resource-3', 4)),
            new WarehouseState('warehouse-E', 20, new StateItem('resource-1', 5), new StateItem('resource-2', 5), new StateItem('resource-3', 5))
        ];

        $itemsToSelect = [
            new Item('resource-1', 8),
            new Item('resource-2', 8),
            new Item('resource-3', 8)
        ];

        //Expected
        $warehousesSelectedExpected = [
            'warehouse-E' => [
                ['resourceId' => 'resource-1', 'quantity' => 5],
                ['resourceId' => 'resource-2', 'quantity' => 5],
                ['resourceId' => 'resource-3', 'quantity' => 5]
            ],
            'warehouse-C' => [
                ['resourceId' => 'resource-1', 'quantity' => 3],
                ['resourceId' => 'resource-2', 'quantity' => 3],
                ['resourceId' => 'resource-3', 'quantity' => 3]
            ]
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

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
            new Item('resource-12', 100),
            new Item('resource-15', 100),
            new Item('resource-21', 100),
            new Item('resource-56', 100),
            new Item('resource-88', 100),
            new Item('resource-32', 100),
            new Item('resource-3', 100),
            new Item('resource-50', 100),
            new Item('resource-99', 100)
        ];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

        for ($i = 1; $i <= 100; $i++) {
            $this->assertEquals([
                ['resourceId' => 'resource-1', 'quantity' => 1],
                ['resourceId' => 'resource-12', 'quantity' => 1],
                ['resourceId' => 'resource-15', 'quantity' => 1],
                ['resourceId' => 'resource-21', 'quantity' => 1],
                ['resourceId' => 'resource-56', 'quantity' => 1],
                ['resourceId' => 'resource-88', 'quantity' => 1],
                ['resourceId' => 'resource-32', 'quantity' => 1],
                ['resourceId' => 'resource-3', 'quantity' => 1],
                ['resourceId' => 'resource-50', 'quantity' => 1],
                ['resourceId' => 'resource-99', 'quantity' => 1]
            ], $warehouseSelected['warehouse-' . $i]);
        }
    }

    public function testWithOnePossibleResultBigData(): void
    {
        //Given
        $warehouseStates = [];
        for ($i = 1; $i <= 98; $i++) {
            $warehouseStates[] = new WarehouseState('warehouse-' . $i, rand(1, 30), ...[new StateItem('resource-0', rand(1, 13))]);
        }

        $warehouseStates[] = new WarehouseState('warehouse-99', 50, new StateItem('resource-0', 22));
        $warehouseStates[] = new WarehouseState('warehouse-100', 18, new StateItem('resource-0', 30));

        $itemsToSelect = [
            new Item('resource-0', 15)
        ];

        //Expected
        $warehousesSelectedExpected = ['warehouse-100' => [['resourceId' => 'resource-0', 'quantity' => 15]]];

        //When
        $warehouseSelected = $this->warehouseSelector->selectWarehouses(new States(...$warehouseStates), new Items(...$itemsToSelect));

        $this->assertEquals($warehousesSelectedExpected, $warehouseSelected);
    }

}