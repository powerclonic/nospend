<?php

namespace App\Enums;

enum Status
{
    case AWAITING_PAYMENT;
    case PAID;
    case EXPIRED;
}
