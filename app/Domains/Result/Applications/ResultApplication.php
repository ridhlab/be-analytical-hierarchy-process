<?php

namespace App\Domains\Result\Applications;

use App\Domains\MatrixCompare\Applications\MatrixCompareApplication;
use App\Models\InputValue;
use App\Models\Result;
use App\Models\VariableInput;
use App\Models\VariableOutput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResultApplication
{
    protected MatrixCompareApplication $matrixCompareApplication;

    public function __construct(MatrixCompareApplication $matrixCompareApplication)
    {
        $this->matrixCompareApplication = $matrixCompareApplication;
    }

    public function getByUserLogin(Request $request)
    {
        return  Result::with('inputValues')->where('user_id', $request->user()->id)->get();
    }

    public function getById($id)
    {
        return Result::with('inputValues')->findOrFail($id);
    }

    public function predict(Request $request)
    {
        $userId = Auth::user()->id;
        $payload = $request->json()->all();
        $dataInputValues = $payload['data_input'];
        $idsInputIdRequest = collect($dataInputValues)->map(function ($item) {
            return $item['variable_input_id'];
        })->all();

        $allVariableInputIdAvailable = VariableInput::all('id')->map(function ($item) {
            return $item->id;
        })->all();


        if ($this->checkDuplicateInputIdInRequest($idsInputIdRequest)) {
            throw new HttpException(400, 'There is duplicate variable input id');
        }

        if (!$this->checkMatchInputIdInRequestAndDb($idsInputIdRequest, $allVariableInputIdAvailable)) {
            throw new HttpException(400, 'Variable input id not match between request and table database');
        }

        // Store result
        DB::beginTransaction();
        $result = Result::create(['name' => $payload['name'], 'user_id' => $userId]);
        $inputValuesCollect = collect();
        foreach ($dataInputValues as $inputValue) {
            $instanceInputValue = new InputValue();
            $instanceInputValue->variable_input_id = $inputValue['variable_input_id'];
            $instanceInputValue->value = $inputValue['value'];
            $inputValuesCollect->add($instanceInputValue);
        }
        $result->inputValues()->saveMany($inputValuesCollect->all());
        DB::commit();

        // Calculate predict
        $outputIds = VariableOutput::all('id')->map(fn ($item) => $item->id);
        $resultPredict =  $outputIds->map(function ($id) use ($dataInputValues) {
            $dataWeights = $this->matrixCompareApplication->getWeightsByVariableOutputId($id);
            $valuePredict = 0;
            foreach ($dataInputValues as $inputValue) {
                $currentWeight =  collect($dataWeights['weights'])->filter(fn ($item) => $item['variableInputId'] == $inputValue['variable_input_id'])->first();
                $valuePredict += $inputValue['value'] * $currentWeight['weight'];
            }
            return [
                'variableOutputId' => $dataWeights['variableOutputId'],
                'variableOutputName' => $dataWeights['variableOutputName'],
                'value' => $valuePredict
            ];
        })->sortByDesc('value')->values();

        return [
            'resultId' => $result->id,
            'name' => $payload['name'],
            'predict' => $resultPredict,
        ];
    }

    public function checkMatchInputIdInRequestAndDb($idsRequest, $idsDb)
    {
        for ($i = 0; $i < count($idsRequest); $i++) {
            if (!in_array($idsRequest[$i], $idsDb)) {
                return false;
            }
        }

        if (count($idsRequest) != count($idsDb)) {
            return false;
        }

        return true;
    }

    public function checkDuplicateInputIdInRequest($ids)
    {
        if (count(collect($ids)->duplicates()) > 0) {
            return true;
        }
        return false;
    }
}
