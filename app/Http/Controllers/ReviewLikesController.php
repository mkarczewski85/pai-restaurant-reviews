<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewLikes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewLikesController extends Controller
{
    public function likeReview($reviewId)
    {
        $user = Auth::user();

        $review = Review::findOrFail($reviewId);

        $reviewLike = ReviewLikes::create([
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);

        $review->recalculateReviewStats();

        return response()->json(null, 204);
    }

    public function unlikeReview($reviewId)
    {
        $user = Auth::user();

        $review = Review::findOrFail($reviewId);

        ReviewLikes::where('review_id', $review->id)
            ->where('user_id', $user->id)
            ->delete();

        $review->recalculateReviewStats();

        return response()->json(null, 204);
    }
}
