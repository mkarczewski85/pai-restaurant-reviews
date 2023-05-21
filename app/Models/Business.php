<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Business extends Model
{
    protected $table = 'businesses';

    protected $fillable = ['name', 'description', 'address', 'city', 'state', 'zip_code', 'price_level', 'avg_rating', 'business_category_id'];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function businessCategory(): HasOne
    {
        return $this->hasOne(BusinessCategory::class);
    }

}
