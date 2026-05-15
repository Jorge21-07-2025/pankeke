<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyAssignment extends Model
{
    protected $fillable = [
        'emergency_id',
        'user_id',
        'message',
        'status',
    ];

    public function emergency(): BelongsTo
    {
        return $this->belongsTo(Emergency::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
