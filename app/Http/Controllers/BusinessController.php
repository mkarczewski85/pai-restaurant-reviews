<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return Business::join('business_categories', 'businesses.business_category_id', '=', 'business_categories.id')
            ->leftJoin('favourites', function($join) use ($user)
            {
                $join->on('favourites.business_id', '=', 'businesses.id');
                $join->on('favourites.user_id', '=', DB::raw($user->id));
            })
            ->selectRaw('businesses.*, business_categories.name AS category_name, CASE WHEN favourites.business_id IS NOT NULL THEN "true" ELSE "false" END AS is_favourite')
            ->get();

    }

    public function show(Business $business)
    {
        return $business;
    }

    public function getBusinessDetails($id)
    {
        return Business::join('business_categories', 'businesses.business_category_id', '=', 'business_categories.id')
            ->select('businesses.*', 'business_categories.name AS category_name')
            ->where('businesses.id', $id)
            ->first();
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
