<?php

namespace App\Domains\MatrixCompare\Applications;

use App\Http\Requests\MatrixCompare\StoreMatrixCompareRequest;
use App\Http\Requests\MatrixCompare\UpdateValueMatrixCompareRequest;
use App\Models\MatrixCompare;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MatrixCompareApplication
{
    public function store(StoreMatrixCompareRequest $request)
    {
        $variableInputId = $request->validated()['variable_input_id'];
        $compare1VariableOutputId = $request->validated()['compare1_variable_output_id'];
        $compare2VariableOutputId = $request->validated()['compare2_variable_output_id'];
        $value = $request->validated()['value'];

        $compareOutputIdExist = count(MatrixCompare::where('compare1_variable_output_id', $compare1VariableOutputId)
            ->where('compare2_variable_output_id', $compare2VariableOutputId)
            ->where('variable_input_id', $variableInputId)
            ->get()) > 0 || count(MatrixCompare::where('compare1_variable_output_id', $compare2VariableOutputId)
            ->where('compare2_variable_output_id', $compare1VariableOutputId)
            ->where('variable_input_id', $variableInputId)
            ->get()) > 0;

        if ($compareOutputIdExist) {
            throw new HttpException(400, 'Compare variable output id is exist');
        }

        DB::beginTransaction();

        if (($compare1VariableOutputId == $compare2VariableOutputId) && $value != 1) {
            throw new HttpException(400, 'If variable output compare is same, value must be 1');
        }
        if ($compare1VariableOutputId == $compare2VariableOutputId) {
            $this->createMatrixCompare($variableInputId, $compare1VariableOutputId, $compare2VariableOutputId, $value);
        } else {
            $this->createMatrixCompare($variableInputId, $compare1VariableOutputId, $compare2VariableOutputId, $value);
            $this->createMatrixCompare($variableInputId, $compare2VariableOutputId, $compare1VariableOutputId, 1 / $value);
        }

        DB::commit();
        return true;
    }

    public function update(UpdateValueMatrixCompareRequest $request, $id)
    {
        $value = $request->validated()['value'];

        DB::beginTransaction();
        $matrixCompare = MatrixCompare::findOrFail($id);
        $compare1VariableOutputId = $matrixCompare->compare1_variable_output_id;
        $compare2VariableOutputId = $matrixCompare->compare2_variable_output_id;

        if (($compare1VariableOutputId == $compare2VariableOutputId) && $value != 1) {
            throw new HttpException(400, 'If variable output compare is same, value must be 1');
        }

        $matrixCompare->value = $value;
        if ($compare1VariableOutputId != $compare2VariableOutputId) {
            $matrixCompareOppsite = MatrixCompare::where('compare1_variable_output_id', $compare2VariableOutputId)
                ->where('compare2_variable_output_id', $compare1VariableOutputId)->first();
            $matrixCompareOppsite->value = 1 / $value;
            $matrixCompareOppsite->save();
        }
        $matrixCompare->save();
        DB::commit();
        return true;
    }

    public function createMatrixCompare(
        $variableInputId,
        $compare1VariableOutputId,
        $compare2VariableOutputId,
        $value
    ) {
        $matrixCompare = new MatrixCompare();
        $matrixCompare->variable_input_id = $variableInputId;
        $matrixCompare->compare1_variable_output_id = $compare1VariableOutputId;
        $matrixCompare->compare2_variable_output_id = $compare2VariableOutputId;
        $matrixCompare->value = $value;
        $matrixCompare->save();
    }
}
