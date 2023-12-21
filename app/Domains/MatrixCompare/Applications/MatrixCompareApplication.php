<?php

namespace App\Domains\MatrixCompare\Applications;

use App\Http\Requests\MatrixCompare\StoreUpdateMatrixCompareRequest;
use App\Models\MatrixCompare;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MatrixCompareApplication
{

    public function store(StoreUpdateMatrixCompareRequest $request)
    {

        $variableInputId = $request->validated()['variable_input_id'];
        $compare1VariableOutputId = $request->validated()['compare1_variable_output_id'];
        $compare2VariableOutputId = $request->validated()['compare2_variable_output_id'];
        $value = $request->validated()['value'];

        $compareOutputIdExist = count(MatrixCompare::where('compare1_variable_output_id', $compare1VariableOutputId)
            ->where('compare2_variable_output_id', $compare2VariableOutputId)
            ->get()) > 0 || count(MatrixCompare::where('compare1_variable_output_id', $compare2VariableOutputId)
            ->where('compare2_variable_output_id', $compare1VariableOutputId)
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
        }

        $this->createMatrixCompare($variableInputId, $compare1VariableOutputId, $compare2VariableOutputId, $value);
        $this->createMatrixCompare($variableInputId, $compare2VariableOutputId, $compare1VariableOutputId, 1 / $value);


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
