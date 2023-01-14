<?php

namespace src\Order\Application\Handler;

use src\Order\Application\Command\CreateOrder\CreateOrderCommand;
use src\Order\Domain\Item;
use src\Order\Domain\Items;
use src\Order\Domain\Order;
use src\Order\Domain\OrderRepositoryInterface;

class CreateOrderHandler
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(CreateOrderCommand $command)
    {
        $items = Items::createEmpty();

        foreach ($command->getItems() as $itemCommand) {
            $items->add(new Item($itemCommand->getProductId(), $itemCommand->getQuantity()));
        }

        $this->orderRepository->add(Order::createInitial($command->getId(), $items, $command->getUserId()));
    }
}