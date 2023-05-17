<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        return Business::join('business_categories', 'businesses.business_category_id', '=', 'business_categories.id')
            ->select('businesses.*', 'business_categories.name AS category_name')
            ->get();
    }

    public function show(Business $business)
    {
        return $business;
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
