<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewLocation extends Model
{
    // Point the model to your specific table name
    protected $table = 'new_locations'; // Force the table name
    protected $fillable = ['user_id', 'type', 'address', 'latitude', 'longitude', 'is_active'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
