<?php

namespace App\Http\Controllers\auth;


use App\Models\Favourite;
use App\Models\Review;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => array(
                        'required',
                        'regex:/^(?=.*[A-Z])(?=.*\d)(?=.{1,}[!@#$%&*()-=_+{};":|,.<>\/?])\S{6,}$/'
                    )
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Sukces.',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateuser(Request $request, User $user)
    {
        $user->update($request->all());

        return response()->json($user);
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Wprowadzone hasło lub email są nieprawidłowe.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'Sukces.',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function userDetails()
    {
        $user = Auth::user();

        $user->review_stats = Review::select(DB::raw('count(*) as reviews_count, sum(likes_count) as likes_count'))
            ->where('user_id', $user->id)
            ->groupBy('user_id')
            ->first();
        $user->review_stats->count_favorites = Favourite::where('user_id', $user->id)->count();
        $user->review_stats->likes_count = intval($user->review_stats->likes_count);
        return $user;
    }
}
