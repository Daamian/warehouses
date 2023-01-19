<?php

namespace Daamian\WarehouseAlgorithm\Order\Domain;

interface OrderRepositoryInterface
{
    public function add(Order $order): void;
    public function find(string $orderId): Order;
    public function update(Order $order): void;
}