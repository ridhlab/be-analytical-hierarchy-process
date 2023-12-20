<?php

namespace App\Http\Controllers\Api\VariableInput;

use App\Domains\VariableInput\Applications\VariableInputCrudApplication;
use App\Http\Controllers\Controller;
use App\Http\Requests\VariableInput\StoreUpdateVariableInputRequest;
use App\Shareds\ApiResponser;

class VariableInputController extends Controller
{

    private VariableInputCrudApplication $variableInputCrudApplication;

    public function __construct(VariableInputCrudApplication $variableInputCrudApplication)
    {
        $this->variableInputCrudApplication = $variableInputCrudApplication;
    }

    public function index()
    {
        $data = $this->variableInputCrudApplication->index();
        return ApiResponser::successResponser($data, 'Get data successfully');
    }

    public function show($id)
    {
        $data = $this->variableInputCrudApplication->show($id);
        return ApiResponser::successResponser($data, 'Get data succesfully');
    }

    public function store(StoreUpdateVariableInputRequest $request)
    {
        $data = $this->variableInputCrudApplication->store($request);
        return ApiResponser::successResponser($data, ApiResponser::generateMessageStore('variable input'));
    }

    public function update(StoreUpdateVariableInputRequest $request, $id)
    {
        $data = $this->variableInputCrudApplication->update($id, $request);
        return ApiResponser::successResponser($data, ApiResponser::generateMessageUpdate('variable input'));
    }
}
