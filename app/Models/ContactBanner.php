<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactBanner extends Model
{
    protected $fillable = ['title', 'image', 'is_active'];
}
