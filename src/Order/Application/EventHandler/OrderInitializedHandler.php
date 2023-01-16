<?php

namespace Daamian\WarehouseAlgorithm\Order\Application\EventHandler;

use Daamian\WarehouseAlgorithm\Order\Application\Command\ConfirmOrder\ConfirmOrderCommand;
use Daamian\WarehouseAlgorithm\Order\Application\Command\RejectOrder\RejectOrderCommand;
use Daamian\WarehouseAlgorithm\Order\Domain\Event\OrderInitialized;
use Daamian\WarehouseAlgorithm\Order\Domain\Item as OrderItem;
use Daamian\WarehouseAlgorithm\Shared\CommandBusInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\AvailabilityInterface;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\DTO\Item as ItemDTO;
use Daamian\WarehouseAlgorithm\Warehouse\Application\Service\NotEnoughQuantityOfResourceException;

class OrderInitializedHandler
{
    private AvailabilityInterface $availability;
    private CommandBusInterface $commandBus;

    public function __construct(AvailabilityInterface $availability, CommandBusInterface $commandBus)
    {
        $this->availability = $availability;
        $this->commandBus = $commandBus;
    }

    public function __invoke(OrderInitialized $event): void
    {
        try {
            $this->availability->blockItems($event->getId(), ...array_map(function (OrderItem $item) {
                return new ItemDTO($item->getProductId(), $item->getQuantity());
            }, $event->getItems()->toArray()));
        } catch (NotEnoughQuantityOfResourceException $exception) {
            $this->commandBus->dispatch(new RejectOrderCommand($event->getId()));
            return;
        }

        $this->commandBus->dispatch(new ConfirmOrderCommand($event->getId()));
    }
}