<?php

namespace App\Domains\VariableInput\Applications;

use App\Http\Requests\VariableInput\StoreVariableInputRequest;
use App\Models\VariableInput;
use Illuminate\Database\Eloquent\Collection;

class VariableInputCrudApplication
{
    public function store(StoreVariableInputRequest $request): VariableInput
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
}
