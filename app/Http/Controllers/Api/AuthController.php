<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\HandleLoginLink;
use App\Models\LoginLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $data = \request()->all();
        $valid = Validator::make($data,[
            'email'=>'required'
        ]);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'errors' => [
                'email' => [
                    'Email not found, please register'
                ]
            ]], 422);
        }
        $expires = now()->addMinutes(30);
        $loginLink = LoginLink::create([
            'user_id' => $user->id,
            'token' => Str::random(32),
            'link' => '',
            'expired_at' => $expires,
        ]);

        $frontendLink = URL::signedRoute('login', [
            'user_id' => $user->id,
            'expires_at' => $expires,
        ]);

        $loginLink->link = $frontendLink;
        $loginLink->save();
        $url = $loginLink->link;
        $urlComponents = parse_url($url);
        parse_str($urlComponents['query'], $queryParams);
        $signature = isset($queryParams['signature']) ? $queryParams['signature'] : null;
        $frontendLink = env('FRONTEND_LINK') . "login-user?user_id={$user->id}&expires_at={$expires}&signature={$signature}";
        HandleLoginLink::dispatch($user, $frontendLink);
        return response()->json([
            'message' => 'Login link sent to your email.'], 200);
    }

    public function register(Request $request){
        $rules = [
            'email'=>'required|unique:users',
            'name'=>'required',
        ];
        $data = \request()->all();
        $valid = Validator::make($data,$rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ], 422);
        }
        $user = User::create([
            'name'=>\request('name'),
            'email'=>\request('email'),
        ]);
        if($user) {
            return response()->json(['message' => 'Registered success link sent to your email.'], 200);
        }
        return response()->json(['message' => 'Registered failed.'], 422);
    }
    public function logout() {

        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response([
            'user' => 'User logged out successfully.',
        ]);
    }

    public function loggedInUser () {

        $user = Auth::user();
       return response([
            'user' => $user,
        ]);
    }
    public function loginWithLink(Request $request, $user_id, $expires_at)
    {
        if (!URL::hasValidSignature($request)) {
            abort(401, 'Invalid or expired login link.');
        }
        if (Carbon::parse($expires_at)->isPast()) {
            abort(401, 'Login link has expired.');
        }
        $user = User::find($user_id);
        if (!$user) {
            abort(404, 'User not found.');
        }
        $token = JWTAuth::fromUser($user);
        return response()->json(['access_token' => $token, 'user' => $user]);
    }

}
