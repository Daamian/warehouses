<?php

namespace src\Order\Application\Handler;

use src\Order\Application\Command\ConfirmOrder\ConfirmOrderCommand;
use src\Order\Domain\OrderRepositoryInterface;

class ConfirmOrderHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(ConfirmOrderCommand $command): void
    {
        $order = $this->orderRepository->find($command->getOrderId());
        $order->confirm();
        $this->orderRepository->update($order);
    }
}