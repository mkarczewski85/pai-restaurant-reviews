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
use stdClass;
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

    public function updateUserData(Request $request)
    {
        $validateData = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
            ]);

        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateData->errors()
            ], 401);
        }
        $user = Auth::user();

        $emailTaken = User::where('email', $request->input('email'))->where('id', '!=', $user->id)->count() > 0;
        if ($emailTaken) {
            return response()->json(['message' => 'Podany adres email jest już używany'], 400);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        return $user;
    }

    public function changePassword(Request $request)
    {
        $validatePassword = Validator::make($request->all(),
            [
                'new_password' => array(
                    'required',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.{1,}[!@#$%&*()-=_+{};":|,.<>\/?])\S{6,}$/'
                ),
                'current_password' => array('required'),
                'confirm_password' => array('required'),
            ]);

        if ($validatePassword->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatePassword->errors()
            ], 401);
        }
        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'Aktualne hasło jest niepoprawne.'], 400);
        }

        if ($request->input('new_password') !== $request->input('confirm_password')) {
            return response()->json(['message' => 'Nowe hasło i potwierdzenie hasła nie są identyczne.'], 400);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json(['message' => 'Hasło zostało zmienione.']);
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

        $review_stats = Review::select(DB::raw('count(*) as reviews_count, sum(likes_count) as likes_count'))
            ->where('user_id', $user->id)
            ->groupBy('user_id')
            ->first();
        if ($review_stats === null) {
            $review_stats = new stdClass();
            $review_stats->reviews_count = 0;
            $review_stats->likes_count = 0;
        }
        $review_stats->count_favorites = Favourite::where('user_id', $user->id)->count();
        $review_stats->likes_count = intval($review_stats->likes_count);
        $user->review_stats = $review_stats;
        return $user;
    }
}
