<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case ACTIVE = 'active';
    case NOTACTIVE = 'not_active';
}
