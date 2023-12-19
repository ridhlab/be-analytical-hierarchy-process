<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domains\Auth\Applications\AuthApplication;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
        $response = $this->authApplication->login($request);
        return response($response);
    }
}
