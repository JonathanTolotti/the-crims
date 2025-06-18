<?php

namespace App\Models;

use App\Enums\EquipmentSlotEnum;
use App\Enums\ItemTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Item extends Model
{
    protected $guarded = [];

    protected $casts = [
        'item_type' => ItemTypeEnum::class,
        'equipment_slot' => EquipmentSlotEnum::class,
    ];

    public function userItems(): HasMany
    {
        return $this->hasMany(UserItem::class);
    }

    public function droppedByCrimes(): BelongsToMany
    {
        return $this->belongsToMany(Crime::class, 'crime_item')
            ->withPivot('drop_chance');
    }

    public function requiredLevel(): BelongsTo
    {
        return $this->belongsTo(LevelDefinition::class, 'required_level_id');
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
