<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
