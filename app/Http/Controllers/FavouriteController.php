<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function addToFavorites($businessId)
    {
        $user = Auth::user();

        $favorite = Favourite::create([
            'user_id' => $user->id,
            'business_id' => $businessId,
        ]);
        return response()->json(null, 204);
    }

    public function removeFromFavorites($businessId)
    {
        $user = Auth::user();

        Favourite::where('business_id', $businessId)
            ->where('user_id', $user->id)
            ->delete();

        return response()->json(null, 204);
    }

    public function getFavorites()
    {
        $user = Auth::user();
        return Favourite::where('user_id', $user->id)
            ->get();
    }
}
