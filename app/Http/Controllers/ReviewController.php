<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::all();
    }

    public function show(Review $review)
    {
        return $review;
    }

    public function store(Request $request)
    {
        // create new Review
        $review = new Review();

        // retrieve logged user context data and set user_id
        $user = Auth::user();
        $user_id = $user->id;
        $review->user_id = $user_id;

        // find Business and set business_id or throw ex if not exists
        $business = Business::findOrFail($request->business_id);
        $review->business_id = $business->id;

        // fill input fields for Review
        $input = $request->all();
        $review->rating = $input->rating;
        $review->review_text = $input->review_text;
        $review->userful_count = 0;

        // save Review
        $review>save();

        // recalculate avg score for Business and save
        $avg_rating = $business->reviews()->avg('rating');
        $business->avg_rating = $avg_rating;
        $business->save();

        return response()->json($review);
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->all());

        return response()->json($review);
    }

    public function delete(Review $review)
    {
        $review->delete();

        return response()->json(null, 204);
    }
}
