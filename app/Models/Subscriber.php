<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    // Table is automatically 'subscribers' (Laravel convention)
    protected $fillable = [
        'email',
        'token',
        'verified_at',
    ];

    // Cast verified_at as datetime
    protected $dates = [
        'verified_at',
    ];

    /**
     * Check if subscriber is verified
     */
    public function isVerified()
    {
        return !is_null($this->verified_at);
    }
}
