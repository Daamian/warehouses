<?php

namespace Daamian\WarehouseAlgorithm\Order\Domain;

enum Status
{
    case INITIAL;
    case CONFIRMED;
    case REJECTED;
}