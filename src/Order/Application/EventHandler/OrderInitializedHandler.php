<?php

namespace src\Order\Application\EventHandler;

use src\Order\Application\Command\ConfirmOrder\ConfirmOrderCommand;
use src\Order\Application\Command\RejectOrder\RejectOrderCommand;
use src\Order\Domain\Event\OrderInitialized;
use src\Order\Domain\Item as OrderItem;
use src\Shared\CommandBusInterface;
use src\Warehouse\Application\Service\AvailabilityInterface;
use src\Warehouse\Application\Service\DTO\Item as ItemDTO;
use src\Warehouse\Application\Service\NotEnoughQuantityOfResourceException;

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