<?php

namespace App\Domains\Auth\Applications;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Shareds\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthApplication
{

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->validated()['name'];
        $user->email = $request->validated()['email'];
        $user->password = bcrypt($request->validated()['password']);
        $user->save();

        $token = $this->generateToken($user);
        return ApiResponser::successResponser(['user' => $user, 'token' => $token], 'Register successfully');
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return ApiResponser::errorResponse('Email atau password Anda Salah');
        }
        $user = Auth::user();
        $token = $this->generateToken($user);
        return ApiResponser::successResponser(['user' => $user, 'token' => $token], 'Login successfully');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return ApiResponser::successResponser(null, 'Logout Successfully');
    }

    public function generateToken(User $user)
    {
        return  $user->createToken(config('auth.auth_token'))->plainTextToken;
    }
}
