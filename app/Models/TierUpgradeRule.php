<?php

namespace App\Models;

use App\Enums\RefineFailureOutcomeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TierUpgradeRule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'failure_outcome' => RefineFailureOutcomeEnum::class,
    ];

    public function requiredItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'required_item_id');
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
