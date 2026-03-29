<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    protected $fillable = ['title', 'subtitle', 'image1', 'image2', 'image3', 'is_active'];
}
