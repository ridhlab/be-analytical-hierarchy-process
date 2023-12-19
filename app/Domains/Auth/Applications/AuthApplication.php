<?php

namespace App\Domains\Auth\Applications;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Shareds\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthApplication
{
    public function login(LoginRequest $request)
    {
        if ($request->validator->fails()) {
            throw ValidationException::withMessages([ApiResponser::unprocessableEntity => $request->validator->getMessageBag()]);
        }
        if (!Auth::attempt($request->validated())) {
            return ApiResponser::errorResponse('Email atau password Anda Salah');
        }
        $user = Auth::user();
        $token = $this->generateToken($user);
        return ApiResponser::successResponser([$user, 'token' => $token]);
    }

    public function generateToken(User $user)
    {
        return  $user->createToken(config('auth.auth_token'))->plainTextToken;
    }
}
