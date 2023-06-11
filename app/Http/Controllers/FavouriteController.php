<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Favourite;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function getFavorites(Request $request)
    {
        $user = Auth::user();
        $limit = $request->limit;
        $offset = $request->offset;

        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric',
            'offset' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        $data = Business::join('favourites', 'businesses.id', '=', 'favourites.business_id')
            ->where('user_id', $user->id)
            ->selectRaw('businesses.*, true as is_favorite')
            ->distinct()
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($data as &$item) {
            $item['is_favorite'] = boolval($item['is_favorite']);
        }
        return $data;
    }
}
