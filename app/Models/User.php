<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\SellerVerification;
use App\Models\BusinessVerification;
use App\Models\BuyerVerification;
use App\Models\StationVerification;
use App\Models\PreOrder;
use App\Models\UserNotification;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = ['name', 'email', 'phone', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Verification relationships ─────────────────────────────────────

    public function sellerVerification(): HasOne
    {
        return $this->hasOne(SellerVerification::class);
    }

    public function buyerVerification(): HasOne
    {
        return $this->hasOne(BuyerVerification::class);
    }

    public function businessVerification(): HasOne
    {
        return $this->hasOne(BusinessVerification::class);
    }

    public function stationVerification(): HasOne
    {
        return $this->hasOne(StationVerification::class);
    }

    public function garageVerification(): HasOne
    {
        return $this->hasOne(GarageVerification::class);
    }

    public function verification(): SellerVerification|BuyerVerification|BusinessVerification|StationVerification|GarageVerification|null
    {
        return $this->sellerVerification
            ?? $this->buyerVerification
            ?? $this->businessVerification
            ?? $this->stationVerification
            ?? $this->garageVerification;
    }

    // ── Car / marketplace relationships ───────────────────────────────

    public function listedCars(): HasMany
    {
        return $this->hasMany(Car::class, 'seller_id');
    }

    // ── Buyer relationships ────────────────────────────────────────────

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function preOrders(): HasMany
    {
        return $this->hasMany(PreOrder::class, 'buyer_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'buyer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }

    // ── Location ──────────────────────────────────────────────────────

    public function location(): HasOne
    {
        return $this->hasOne(NewLocation::class, 'user_id');
    }

    // ── EV Station relationships ───────────────────────────────────────

    /** Charging slots owned by this EV station */
    public function evStationSlots(): HasMany
    {
        return $this->hasMany(EvStationSlot::class, 'user_id');
    }

    // ── Garage relationships ───────────────────────────────────────────

    /** Appointments received by this garage */
    public function garageAppointments(): HasMany
    {
        return $this->hasMany(GarageAppointment::class, 'garage_user_id');
    }

    /** Appointments booked by this user (as a customer) */
    public function bookedAppointments(): HasMany
    {
        return $this->hasMany(GarageAppointment::class, 'customer_user_id');
    }

    public function notifications(): HasMany
    {
    return $this->hasMany(UserNotification::class);
    }
 
    public function unreadNotificationCount(): int
    {
    return $this->notifications()->whereNull('read_at')->count();
    }
}