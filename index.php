<?php
require_once "vendor/autoload.php";
function selectWarehouses(array $warehouses, array $order) {
    // Keep track of the total amount of each product in each warehouse
    $amounts = [];
    foreach ($warehouses as $warehouse) {
        foreach ($warehouse->products as $product) {
            $amounts[$product->id][$warehouse->id] = $product->amount;
        }
    }

    // Keep track of the selected warehouses for each product
    $selectedWarehouses = [];
    foreach ($order as $productId => $quantity) {
        // Use a priority queue to find the warehouse with the minimum amount of the product
        $minWarehouseId = null;
        $minAmount = INF;
        $queue = new SplPriorityQueue();
        foreach ($amounts[$productId] as $warehouseId => $amount) {
            $queue->insert([
                'warehouseId' => $warehouseId,
                'amount' => $amount,
            ], $amount);
        }
        while (!$queue->isEmpty()) {
            $item = $queue->extract();
            if ($item['amount'] >= $quantity) {
                // Found a warehouse with enough of the product
                $minWarehouseId = $item['warehouseId'];
                $minAmount = $item['amount'];
                break;
            }
        }

        // Subtract the ordered amount from the selected warehouse's total
        $amounts[$productId][$minWarehouseId] -= $quantity;

        // Keep track of the selected warehouse
        $selectedWarehouses[$productId] = $minWarehouseId;
    }

    return $selectedWarehouses;
}



function sortWarehouses($warehouses) {
    usort($warehouses, function($a, $b) {
        return $b['stock'] <=> $a['stock'];
    });
    return $warehouses;
}


$order = 10;
$warehouses = array(
    array("name" => "Magazyn 1", "stock" => 2),
    array("name" => "Magazyn 2", "stock" => 5),
    array("name" => "Magazyn 3", "stock" => 9),
);
$selectedWarehouses = selectWarehouses($order, $warehouses);
var_dump($selectedWarehouses);
exit();
foreach ($selectedWarehouses as $warehouse) {
    echo "Wybrano magazyn " . $warehouse['name'] . " z ilością produktów: " . $warehouse['stock'] . "\n";
}

