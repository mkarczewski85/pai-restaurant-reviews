<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::all();
    }

    public function getBusinessReviews($businessId)
    {
        $user = Auth::user();
        return Review::join('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'users.name AS author')
            ->where('business_id', $businessId)
            ->where('user_id', '!=', $user->id)
            ->get();
    }

    public function getMyReview($businessId)
    {
        $user = Auth::user();
        return Review::select('reviews.*')
            ->where('business_id', $businessId)
            ->where('user_id', $user->id)
            ->first();
    }

    public function show(Review $review)
    {
        return $review;
    }

    public function storeMyReview(Request $request, $businessId)
    {
        // create new Review.vue

        // retrieve logged user context data and set user_id
        $user = Auth::user();

        // find Business and set business_id or throw ex if not exists
        $business = Business::findOrFail($businessId);

        // fill input fields for Review
        $input = $request->all();

        // save Review
        $review = Review::create([
            'user_id' => $user->id,
            'business_id' => $business->id,
            'rating' => $input['rating'],
            'review_text' => $input['review_text'],
            'useful_count' => 0,
        ]);

        $business->recalculateBusinessStats();
        return response()->json($review);
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->all());

        return response()->json($review);
    }

    public function deleteMyReview(Request $request, $businessId)
    {
        $user = Auth::user();
        $business = Business::findOrFail($businessId);
        Review::where('business_id', $business->id)
            ->where('user_id', $user->id)
            ->delete();
        $business->recalculateBusinessStats();
        return response()->json(null, 204);
    }
}
