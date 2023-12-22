<?php

namespace App\Http\Controllers\Api\MatrixCompare;

use App\Domains\MatrixCompare\Applications\MatrixCompareApplication;
use App\Http\Requests\MatrixCompare\StoreMatrixCompareRequest;
use App\Http\Requests\MatrixCompare\UpdateValueMatrixCompareRequest;
use App\Shareds\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MatrixCompareController
{
    private MatrixCompareApplication $matrixCompareApplication;

    public function __construct(MatrixCompareApplication $matrixCompareApplication)
    {
        $this->matrixCompareApplication = $matrixCompareApplication;
    }


    public function index()
    {
    }

    public function show($id)
    {
    }

    public function store(StoreMatrixCompareRequest $request)
    {
        try {
            $this->matrixCompareApplication->store($request);
            return ApiResponser::successResponser(null, ApiResponser::generateMessageStore('matrix compare'));
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e instanceof HttpException) {
                return ApiResponser::errorResponse($e->getMessage());
            }
            if ($e instanceof QueryException) {
                return ApiResponser::errorResponse($e->getMessage());
            }
        }
    }

    public function update(UpdateValueMatrixCompareRequest $request, $id)
    {
        try {
            $this->matrixCompareApplication->update($request, $id);
            return ApiResponser::successResponser(null, ApiResponser::generateMessageUpdate('matrix compare'));
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e instanceof HttpException) {
                return ApiResponser::errorResponse($e->getMessage());
            }
            if ($e instanceof QueryException) {
                return ApiResponser::errorResponse($e->getMessage());
            }
            if ($e instanceof ModelNotFoundException) {
                return ApiResponser::errorResponse($e->getMessage());
            }
        }
    }
}
