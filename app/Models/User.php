<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\SellerVerification;
use App\Models\BusinessVerification;
use App\Models\StationVerification;
use App\Models\PreOrder;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // tells spatie that all roles/permissions on this model use the 'web' guard
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function sellerVerification(): HasOne
    {
        return $this->hasOne(SellerVerification::class);
    }

    public function businessVerification(): HasOne
    {
        return $this->hasOne(BusinessVerification::class);
    }

    public function stationVerification(): HasOne
    {
        return $this->hasOne(StationVerification::class);
    }

    // helper — works for both roles
    public function verification(): SellerVerification|BusinessVerification|StationVerification|null
    {
        return $this->sellerVerification 
            ?? $this->businessVerification 
            ?? $this->stationVerification;
    }
    // Seller / Business relationships

    /** Cars listed by this user (as seller or business) */
    public function listedCars(): HasMany
    {
        return $this->hasMany(Car::class, 'seller_id');
    }

    // Buyer relationships

    /** Orders placed by this user */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function preOrders(): HasMany
    {
        return $this->hasMany(PreOrder::class, 'buyer_id');
    }

    /** Purchases made by this user */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'buyer_id');
    }

    /** Reviews written by this user */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }
}
