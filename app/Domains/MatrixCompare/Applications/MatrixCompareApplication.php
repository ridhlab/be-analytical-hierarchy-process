<?php

namespace App\Domains\MatrixCompare\Applications;

use App\Http\Requests\MatrixCompare\StoreMatrixCompareRequest;
use App\Http\Requests\MatrixCompare\UpdateValueMatrixCompareRequest;
use App\Models\MatrixCompare;
use App\Models\VariableInput;
use App\Models\VariableOutput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MatrixCompareApplication
{
    public function getByVariabelInputId(string $id): array
    {
        $variableInputName = VariableInput::getNameById($id);
        return [
            'variableInputName' => $variableInputName,
            'variableInputId' => $id,
            'matrixCompares' => MatrixCompare::with('compare1VariableOutput', 'compare2VariableOutput')->where('variable_input_id', $id)->get()
        ];
    }

    public function getByVariableInputIdAndCompare2OutputId($inputId, $compare2OutputId): Collection
    {
        return MatrixCompare::where('variable_input_id', $inputId)
            ->where('compare2_variable_output_id', $compare2OutputId)
            ->get();
    }

    public function getWeightsByVariableOutputId($id)
    {
        return ([
            'variableOutputId' => (int)$id,
            'variableOutputName' => VariableOutput::getNameById($id),
            'weights' => VariableInput::all(['id', 'name'])->map(function ($variableInput) use ($id) {
                $weights = $this->getWeightsByVariableInputId($variableInput->id);
                return [
                    'variableInputId' => $weights['variableInputId'],
                    'variableInputName' => $weights['variableInputName'],
                    'weight' => collect($weights['weights'])->filter(function ($weight) use ($id) {
                        return $weight['variableOutputId'] == $id;
                    })->first()['weight']
                ];
            })->all()
        ]);
    }

    public function getWeightsByVariableInputId($id)
    {
        $dataNormalizations = $this->getNormalizationByVariableInputId($id);
        $arr = [];
        foreach ($dataNormalizations as $normalization) {
            foreach ($normalization['normalization'] as $itemNormalization) {
                array_push($arr, $itemNormalization);
            }
        }
        $mappingNormalization = collect($arr)->groupBy('compare1_variable_output_id')->map(function ($item, $key) {
            return ['compare1VariableOutputId' => $key, 'normalization' => $item];
        })->values();

        return [
            'variableInputId' => (int)$id,
            'variableInputName' => VariableInput::getNameById($id),
            'weights' => collect($mappingNormalization)->map(function ($normalization) {
                $sum = 0;
                foreach ($normalization['normalization'] as $item) {
                    $sum += $item['valueNormalization'];
                }
                return [
                    'variableOutputId' => $normalization['compare1VariableOutputId'],
                    'variableOutputName' => VariableOutput::getNameById($normalization['compare1VariableOutputId']),
                    'weight' => $sum / count($normalization['normalization'])
                ];
            })
        ];
    }

    public function getNormalizationByVariableInputId(string $id)
    {
        $mappingTotal = $this->getTotalCompares($id);
        $normalization = [];
        foreach ($mappingTotal as $mappingTotalItem) {
            $totalCompares = $mappingTotalItem['total'];
            $outputId = $mappingTotalItem['compare2_variable_output_id'];
            $data = $this->getByVariableInputIdAndCompare2OutputId($id, $outputId);
            $tempNormalization = $data->map(function ($item, $key) use ($totalCompares) {
                $valueNormalization = $item['value'] / $totalCompares;
                $item['valueNormalization'] = $valueNormalization;
                $item['totalCompares'] = $totalCompares;
                return $item;
            })->values()->toArray();
            array_push($normalization, ['compare2VariableOutputId' => $outputId, 'normalization' => $tempNormalization]);
        }
        return $normalization;
    }

    public function getTotalCompares(string $variableInputId)
    {
        $matrixCompare = $this->getByVariabelInputId($variableInputId);
        $grouped = (collect($matrixCompare)->map(function ($item, int $key) {
            return [
                'id' => $item['id'],
                'value' => $item['value'],
                'compare2_variable_output_id' => $item['compare2_variable_output_id']
            ];
        }))->groupBy('compare2_variable_output_id');
        return $grouped->map(function ($item, int $key) {
            $total = 0;
            foreach ($item as $value) {
                $total += $value['value'];
            }
            return ['compare2_variable_output_id' => $key, 'total' => $total];
        })->values();
    }

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
        $variableInputId = $matrixCompare->variable_input_id;

        if (($compare1VariableOutputId == $compare2VariableOutputId) && $value != 1) {
            throw new HttpException(400, 'If variable output compare is same, value must be 1');
        }

        $matrixCompare->value = $value;
        if ($compare1VariableOutputId != $compare2VariableOutputId) {
            $matrixCompareOppsite = MatrixCompare::where('variable_input_id', $variableInputId)
                ->where('compare1_variable_output_id', $compare2VariableOutputId)
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
