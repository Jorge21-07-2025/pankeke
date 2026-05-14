<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'breed',
        'age',
        'age_unit',
        'gender',
        'city',
        'weight',
        'size',
        'shelter_id',
        'status',
        'description',
        'image',
        'emoji',
        'color',
        'user_id',
        'vacunado',
        'castrado',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'vacunado' => 'boolean',
            'castrado' => 'boolean',
        ];
    }

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }
}
