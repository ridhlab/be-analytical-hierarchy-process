<?php

namespace App\Http\Controllers\Api\VariableOutput;


use App\Domains\VariableOutput\Applications\VariableOutputCrudApplication;
use App\Http\Controllers\Controller;
use App\Http\Requests\VariableOutput\StoreUpdateVariableOutputRequest;
use App\Shareds\ApiResponser;

class VariableOutputController extends Controller
{
    private VariableOutputCrudApplication $variableOutputCrudApplication;

    public function __construct(VariableOutputCrudApplication $variableOutputCrudApplication)
    {
        $this->variableOutputCrudApplication = $variableOutputCrudApplication;
    }

    public function index()
    {
        $data = $this->variableOutputCrudApplication->index();
        return ApiResponser::successResponser($data, 'Get data successfully');
    }

    public function show($id)
    {
        $data = $this->variableOutputCrudApplication->show($id);
        return ApiResponser::successResponser($data, 'Get data succesfully');
    }

    public function store(StoreUpdateVariableOutputRequest $request)
    {
        $data = $this->variableOutputCrudApplication->store($request);
        return ApiResponser::successResponser($data, ApiResponser::generateMessageStore('variable output'));
    }

    public function update(StoreUpdateVariableOutputRequest $request, $id)
    {
        $data = $this->variableOutputCrudApplication->update($id, $request);
        return ApiResponser::successResponser($data, ApiResponser::generateMessageUpdate('variable output'));
    }
}
