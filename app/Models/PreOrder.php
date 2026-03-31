<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrder extends Model
{
    protected $fillable = [
        'buyer_id', 'car_id', 'deposit_amount', 'payment_method',
        'transaction_ref', 'notes', 'status', 'order_id',
        'buyer_name', 'buyer_phone', 'buyer_email',
    ];

    protected function casts(): array
    {
        return [
            'deposit_amount' => 'integer',
            'placed_at'      => 'datetime',
        ];
    }

    public function buyer(): BelongsTo  { return $this->belongsTo(User::class, 'buyer_id'); }
    public function car(): BelongsTo    { return $this->belongsTo(Car::class); }
    public function order(): BelongsTo  { return $this->belongsTo(Order::class); }

    public function isPendingDeposit(): bool { return $this->status === 'pending_deposit'; }
    public function isDepositPaid(): bool    { return $this->status === 'deposit_paid'; }
    public function isConverted(): bool      { return $this->status === 'converted'; }
    public function isCancellable(): bool    { return in_array($this->status, ['pending_deposit', 'deposit_paid']); }

    public function formattedDeposit(): string
    {
        return 'NRs ' . number_format($this->deposit_amount);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending_deposit' => 'yellow',
            'deposit_paid'    => 'blue',
            'converted'       => 'green',
            'cancelled'       => 'red',
            'refunded'        => 'purple',
            default           => 'gray',
        };
    }
}
