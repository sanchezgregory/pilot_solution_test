<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;


class AuthController extends Controller
{

    public function register(RegisterRequest $request) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);
        
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
        

    }

    public function login(LoginRequest $request){

        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $credentials['email'])->first();

        return response()->json(compact('user', 'token'), 200);
        //return $this->respondWithToken($token);
    }

    // public function logout(){
    //     auth()->logout();

    //     return response()->json(['message' => 'Successfully logged out']);
    // }

    // public function refresh(){
    //     return $this->respondWithToken(auth()->refresh());
    // }


}


