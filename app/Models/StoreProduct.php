<?php

namespace App\Models;

use App\Enums\StoreProductTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StoreProduct extends Model
{
    protected $guarded = [];

    protected $casts = [
        'product_type' => StoreProductTypeEnum::class,
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
