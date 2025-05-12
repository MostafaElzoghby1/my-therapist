<?php
//it is a middle file between request and parent request to override on failedValidation() , 

namespace App\Http\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponce;

class BaseFormRequest extends FormRequest
{
    use ApiResponce;

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            $this->ErrorMessage(
                $validator->errors()->toArray(),
                "Wrong in input data",
                422
            )
        );
    }
}
