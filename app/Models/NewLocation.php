<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewLocation extends Model
{
    protected $table = 'new_locations';

    protected $fillable = [
        'user_id',
        'type',
        'address',
        'latitude',
        'longitude',
        'is_active',
        'total_slots',
        'accepts_walkins',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'accepts_walkins' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}