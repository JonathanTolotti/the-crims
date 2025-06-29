<?php

namespace App\Enums;

enum StoreProductTypeEnum: string
{
    case VIP_SUBSCRIPTION = 'vip_subscription';
    case CASH_PACKAGE = 'cash_package';
}
