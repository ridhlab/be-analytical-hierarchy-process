<?php

namespace App\Domains\VariableInput\Applications;

use App\Http\Requests\VariableInput\StoreUpdateVariableInputRequest;
use App\Models\VariableInput;
use Illuminate\Database\Eloquent\Collection;

class VariableInputCrudApplication
{
    public function store(StoreUpdateVariableInputRequest $request): VariableInput
    {
        $variableInput = new VariableInput();
        $variableInput->name = $request->validated()['name'];
        $variableInput->save();
        return $variableInput;
    }

    public function index(): Collection
    {
        return VariableInput::all();
    }

    public function show($id): VariableInput
    {
        return VariableInput::findOrFail($id);
    }

    public function update($id, StoreUpdateVariableInputRequest $request): VariableInput
    {
        $variableInput = VariableInput::findOrFail($id);
        $variableInput->name = $request->validated()['name'];
        $variableInput->save();
        return $variableInput;
    }
}
