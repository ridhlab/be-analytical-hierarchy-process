<?php

namespace App\Http\Requests;

use App\Shareds\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    public $validator = null;

    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
        throw ValidationException::withMessages([ApiResponser::unprocessableEntity => $validator->getMessageBag()]);
    }
}
