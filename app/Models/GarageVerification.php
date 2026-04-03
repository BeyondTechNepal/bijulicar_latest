<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageVerification extends Model
{
    protected $fillable = ['user_id', 'garage_name', 'contact', 'garage_location', 'specialization', 'license_path', 'status', 'rejection_reason'];

    /**
     * The user that owns this garage verification request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper methods for status checks
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
