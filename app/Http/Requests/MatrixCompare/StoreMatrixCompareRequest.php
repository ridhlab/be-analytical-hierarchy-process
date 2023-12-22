<?php

namespace App\Http\Requests\MatrixCompare;

use App\Http\Requests\BaseRequest;

class StoreMatrixCompareRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'variable_input_id' => ['integer', 'required'],
            'compare1_variable_output_id' => ['integer', 'required'],
            'compare2_variable_output_id' => ['integer', 'required'],
            'value' => ['numeric', 'required']
        ];
    }
}
