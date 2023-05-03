<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'businesses';

    protected $fillable = ['name', 'address', 'city', 'state', 'zip_code', 'avg_rating', 'price_level'];
}
