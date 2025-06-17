<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'strength_modifier',
        'dexterity_modifier',
        'intelligence_modifier',
    ];
}
