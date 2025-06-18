<?php

namespace App\Enums;

enum ItemTypeEnum: string
{
    case EQUIPMENT = 'equipment';
    case CONSUMABLE = 'consumable';
    case REFINING_MATERIAL = 'refining_material';
    case COLLECTIBLE = 'collectible';
}
