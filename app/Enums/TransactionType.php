<?php

namespace App\Enums;

enum TransactionType: string
{
    case discount = '1';
    case deposit = '2';
    case payment = '3';
    case credit = '4';
    case refund = '5';
}
