<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $table = 'businesses';

    protected $fillable = ['name', 'description', 'address', 'city', 'state', 'zip_code', 'price_level', 'avg_rating', 'business_category_id'];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

}
