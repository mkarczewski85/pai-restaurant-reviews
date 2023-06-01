<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewLikes extends Model
{
    protected $table = 'review_likes';

    protected $fillable = ['user_id', 'review_id'];
}
