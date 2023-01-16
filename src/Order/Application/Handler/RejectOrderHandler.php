<?php

namespace Daamian\WarehouseAlgorithm\Order\Application\Handler;

use Daamian\WarehouseAlgorithm\Order\Application\Command\RejectOrder\RejectOrderCommand;
use Daamian\WarehouseAlgorithm\Order\Domain\OrderRepositoryInterface;

class RejectOrderHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(RejectOrderCommand $command): void
    {
        $order = $this->orderRepository->find($command->getOrderId());
        $order->reject();
        $this->orderRepository->update($order);
    }
}