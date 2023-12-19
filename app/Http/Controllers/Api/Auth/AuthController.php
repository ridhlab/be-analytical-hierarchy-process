<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domains\Auth\Applications\AuthApplication;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private AuthApplication $authApplication;

    public function __construct(AuthApplication $authApplication)
    {
        $this->authApplication = $authApplication;
    }


    public function login(LoginRequest $request)
    {
        return $this->authApplication->login($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->authApplication->register($request);
    }
}
