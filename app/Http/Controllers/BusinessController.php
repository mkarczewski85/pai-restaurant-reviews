<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        return Business::all();
    }

    public function show(Business $business)
    {
        return $business;
    }

    public function store(Request $request)
    {
        $article = Business::create($request->all());

        return response()->json($article);
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
