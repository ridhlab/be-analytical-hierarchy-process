<?php

namespace App\Domains\VariableOutput\Applications;

use App\Http\Requests\VariableOutput\StoreUpdateVariableOutputRequest;
use App\Models\VariableOutput;
use Illuminate\Database\Eloquent\Collection;

class VariableOutputCrudApplication
{
    public function store(StoreUpdateVariableOutputRequest $request): VariableOutput
    {
        $variableOutput = new VariableOutput();
        $variableOutput->name = $request->validated()['name'];
        $variableOutput->save();
        return $variableOutput;
    }

    public function index(): Collection
    {
        return VariableOutput::all();
    }

    public function show($id): VariableOutput
    {
        return VariableOutput::findOrFail($id);
    }

    public function update($id, StoreUpdateVariableOutputRequest $request): VariableOutput
    {
        $variableOutput = VariableOutput::findOrFail($id);
        $variableOutput->name = $request->validated()['name'];
        $variableOutput->save();
        return $variableOutput;
    }
}
