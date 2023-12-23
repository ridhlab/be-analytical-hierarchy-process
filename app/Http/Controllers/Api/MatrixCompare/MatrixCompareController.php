<?php

namespace App\Http\Controllers\Api\MatrixCompare;

use App\Domains\MatrixCompare\Applications\MatrixCompareApplication;
use App\Http\Requests\MatrixCompare\StoreMatrixCompareRequest;
use App\Http\Requests\MatrixCompare\UpdateValueMatrixCompareRequest;
use App\Shareds\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MatrixCompareController
{
    private MatrixCompareApplication $matrixCompareApplication;

    public function __construct(MatrixCompareApplication $matrixCompareApplication)
    {
        $this->matrixCompareApplication = $matrixCompareApplication;
    }


    public function index(Request $request)
    {
        if ($request->has('variable_input_id')) {
            $data = $this->matrixCompareApplication->getByVariabelInputId($request->query('variable_input_id'));
            return ApiResponser::successResponser($data, 'Get data matrix compare succesfully');
        }
    }

    public function normalization(Request $request)
    {
        if ($request->has('variable_input_id')) {
            $data = $this->matrixCompareApplication->getNormalizationByVariableInputId($request->query('variable_input_id'));
            return ApiResponser::successResponser($data, 'Get normalizations successfully');
        }
    }

    public function weight(Request $request)
    {
        if ($request->has('variable_input_id')) {
            $data = $this->matrixCompareApplication->getWeightsByVariableInputId($request->query('variable_input_id'));
            return ApiResponser::successResponser($data, 'Get weights successfully');
        }
        if ($request->has('variable_output_id')) {
            $data = $this->matrixCompareApplication->getWeightsByVariableOutputId($request->query('variable_output_id'));
            return ApiResponser::successResponser($data, 'Get weights successfully');
        }
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
