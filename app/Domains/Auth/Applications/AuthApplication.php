<?php

namespace App\Domains\Auth\Applications;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Shareds\ApiResponser;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthApplication
{

    public function register(RegisterRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->validated()['name'];
            $user->email = $request->validated()['email'];
            $user->password = bcrypt($request->validated()['password']);
            $user->assignRole(Role::where('name', 'user')->first());
            $user->save();

            $token = $this->generateToken($user);
            $user->getAllPermissions();

            return ApiResponser::successResponser(['user' => $user, 'token' => $token], 'Register successfully');
        } catch (\Throwable $e) {
            if ($e instanceof UniqueConstraintViolationException) {
                return ApiResponser::errorResponse('Email is already registered');
            }
        }
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return ApiResponser::errorResponse('Your email or password is wrong');
        }
        $user = Auth::user();
        $user->getAllPermissions();
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
