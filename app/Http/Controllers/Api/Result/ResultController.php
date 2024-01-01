<?php

namespace App\Http\Controllers\Api\Result;

use App\Domains\Result\Applications\ResultApplication;
use App\Shareds\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResultController
{
    private ResultApplication $resultApplication;

    public function __construct(ResultApplication $resultApplication)
    {
        $this->resultApplication = $resultApplication;
    }

    public function getByUserLogin(Request $request)
    {
        $data = $this->resultApplication->getByUserLogin($request);
        return ApiResponser::successResponser($data, 'Get result successfully');
    }

    public function getById($id)
    {
        $data = $this->resultApplication->getById($id);
        return ApiResponser::successResponser($data, 'Get result detail successfully');
    }

    public function predict(Request $request)
    {
        try {
            $predict = $this->resultApplication->predict($request);
            return ApiResponser::successResponser($predict, 'Predict successfully');
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            if ($e instanceof HttpException) {
                return ApiResponser::errorResponse($e->getMessage());
            }
        }
    }
}
