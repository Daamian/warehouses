<?php

namespace Daamian\WarehouseAlgorithm\Order\Application\Handler;

use Daamian\WarehouseAlgorithm\Order\Application\Command\CreateOrder\CreateOrderCommand;
use Daamian\WarehouseAlgorithm\Order\Domain\Item;
use Daamian\WarehouseAlgorithm\Order\Domain\Items;
use Daamian\WarehouseAlgorithm\Order\Domain\Order;
use Daamian\WarehouseAlgorithm\Order\Domain\OrderRepositoryInterface;

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