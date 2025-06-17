<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrimeLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'crime_id',
        'was_successful',
        'money_gained',
        'experience_gained',
        'attempted_at',
    ];

    protected $casts = [
        'was_successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function crime(): BelongsTo
    {
        return $this->belongsTo(Crime::class);
    }
}
