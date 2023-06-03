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

    public function getBusinessReviews(Request $request, $businessId)
    {
        $user = Auth::user();
        $limit = $request->limit;
        $offset = $request->offset;

        $data = Review::join('users', 'reviews.user_id', '=', 'users.id')
            ->leftJoin('review_likes', function($join) use ($user)
            {
                $join->on('review_likes.review_id', '=', 'reviews.id');
                $join->on('review_likes.user_id', '=', DB::raw($user->id));
            })
            ->selectRaw('reviews.*, users.name AS author, CASE WHEN review_likes.review_id IS NOT NULL THEN true ELSE false END AS is_liked')
            ->where('reviews.business_id', $businessId)
            ->where('reviews.user_id', '!=', $user->id)
            ->distinct()
            ->orderBy('likes_count', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();

        foreach ($data as &$item)
        {
            $item['is_liked'] = boolval($item['is_liked']);
        }
        return json_encode($data);
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
            'likes_count' => 0,
        ]);

        $business->recalculateBusinessStats();
        return response()->json($review);
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->all());

        return response()->json($review);
    }

    public function deleteMyReview($businessId)
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
