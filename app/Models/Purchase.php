<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'order_id',
        'buyer_id',
        'payment_method',
        'payment_status',
        'amount_paid',
        'transaction_ref',
        'remarks',
        'purchased_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid'  => 'integer',
            'purchased_at' => 'datetime',
        ];
    }

    //  Relationships 

    /** The order this payment belongs to */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** The buyer who made this purchase */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    //  Helpers

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /** e.g. "NRs 5,500,000" */
    public function formattedAmount(): string
    {
        return 'NRs ' . number_format($this->amount_paid);
    }

    /** Human readable payment method e.g. "Bank Transfer" */
    public function paymentMethodLabel(): string
    {
    return match ($this->payment_method) {
        'cash'          => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'emi'           => 'EMI',
        'other'         => 'Other',
        default         => 'Unknown',
        };
    }
}