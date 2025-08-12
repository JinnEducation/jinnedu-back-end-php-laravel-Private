<?php

namespace App\Enums;

enum TransactionPaymentStatus: string
{
    case INITIATED = 'initiated';
    case CREATED = 'created';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}
