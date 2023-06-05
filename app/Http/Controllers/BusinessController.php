<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->limit;
        $offset = $request->offset;

        $data = Business::join('business_categories', 'businesses.business_category_id', '=', 'business_categories.id')
            ->leftJoin('favourites', function ($join) use ($user) {
                $join->on('favourites.business_id', '=', 'businesses.id');
                $join->on('favourites.user_id', '=', DB::raw($user->id));
            })
            ->selectRaw('businesses.*, business_categories.name AS category_name, CASE WHEN favourites.business_id IS NOT NULL THEN true ELSE false END AS is_favorite')
            ->distinct()
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($data as &$item) {
            $item['is_favorite'] = boolval($item['is_favorite']);
        }
        return json_encode($data);
    }

    public function show(Business $business)
    {
        return $business;
    }

    public function getBusinessDetails($id)
    {
        $user = Auth::user();
        $businessDetails = Business::join('business_categories', 'businesses.business_category_id', '=', 'business_categories.id')
            ->select('businesses.*', 'business_categories.name AS category_name')
            ->where('businesses.id', $id)
            ->first();
        $isFavorite = Favourite::where('business_id', $businessDetails->id)
                ->where('user_id', $user->id)->count() >= 1;
        $businessDetails->is_favorite = $isFavorite;
        return json_encode($businessDetails);
    }

    public function store(Request $request)
    {
        $business = Business::create($request->all());

        return response()->json($business);
    }

    public function update(Request $request, Business $business)
    {
        $business->update($request->all());

        return response()->json($business);
    }

    public function delete(Business $business)
    {
        $business->delete();

        return response()->json(null, 204);
    }
}
