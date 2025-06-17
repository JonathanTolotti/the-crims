<?php

namespace App\Models;

use App\Enums\CharacterAttributeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
