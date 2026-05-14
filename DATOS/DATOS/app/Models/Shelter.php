<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shelter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'phone',
        'description',
    ];

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }
}
