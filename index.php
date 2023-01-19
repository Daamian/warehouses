<?php

use Daamian\WarehouseAlgorithm\Order\Application\Command\CreateOrder\CreateOrderCommand;
use Daamian\WarehouseAlgorithm\Order\Application\Command\CreateOrder\Item;
use Daamian\WarehouseAlgorithm\Shared\CommandBusInterface;

require_once "vendor/autoload.php";

class Ordering
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function simulateManyOrdersAtSameTime(): void
    {
        //Wpadają trzy zamówienia w tym samym czasie
        $command1 = new CreateOrderCommand('order-1', 'user-1', new Item('product-1', 10));
        $command2 = new CreateOrderCommand('order-2', 'user-2', new Item('product-1', 10));
        $command3 = new CreateOrderCommand('order-3', 'user-3', new Item('product-1', 10));

        /* Wrzucamy kolejno zamówienia na szynę, implementacja szyny w warstwie infrastruktury w systemie kolejkowania np. rabbit na tę samą kolejkę
           dzięki czemu zostaną przeprocesowane po jedno po drugim. Każde zamówienie utworzy się ze statusem INITIAL i emituje event również na szynę z systemem kolejkowania który następnie jest obsługiwany w handlerze
           i dopiero wtedy procesujemy blokowanie produktów dla zamówienia, jeżeli serwis dostępności pozwala zablokować aktualizujemy zamówienie na confirmed dzięki czemu
           może przebyć dalszy workflow (kompletowanie, wysyłka itp), w przypadku gdy serwis dostępności nie pozwala zablokować ze względu na brak stanów aktualizujemy na status canceled */
        $this->commandBus->dispatch($command1);
        $this->commandBus->dispatch($command2);
        $this->commandBus->dispatch($command3);
    }
}



