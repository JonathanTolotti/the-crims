<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_to_next_level',
    ];
}
