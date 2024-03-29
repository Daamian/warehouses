<?php

namespace Daamian\WarehouseAlgorithm\Order\Application\Command\ConfirmOrder;

final class ConfirmOrderCommand
{
    private string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}