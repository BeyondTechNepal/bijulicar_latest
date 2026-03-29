<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
    protected $table = "contact_details";

    protected $fillable = [
        'email',
        'phone_no',
        'address',
        'working_hours'
    ];
}
