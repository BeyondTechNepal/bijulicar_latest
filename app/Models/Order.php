<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'car_id',
        'status',
        'total_price',
        'notes',
        'buyer_name',
        'buyer_phone',
        'buyer_email',
        'ordered_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
            'ordered_at'  => 'datetime',
        ];
    }

    // Relationships 

    /** The buyer who placed this order */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /** The car this order is for */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /** The purchase record once payment is made */
    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }

    //  Helpers

    /** Can this order still be cancelled? */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /** Returns a color string for status badges in the view */
    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }
}