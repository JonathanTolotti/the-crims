<?php

namespace App\Models;

use App\Enums\CharacterAttributeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Crime extends Model
{
    protected $fillable = [
        'name',
        'description',
        'energy_cost',
        'money_reward_min',
        'money_reward_max',
        'experience_reward',
        'cooldown_seconds',
        'required_level_id',
        'primary_attribute',
        'base_success_chance',
    ];

    protected $casts = [
        'primary_attribute' => CharacterAttributeEnum::class
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(CrimeLog::class);
    }

    public function possibleLoot(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'crime_item')
            ->withPivot('drop_chance');
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
