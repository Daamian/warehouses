<?php

namespace src\Order\Domain;

enum Status
{
    case INITIAL;
    case CONFIRMED;
    case REJECTED;
}