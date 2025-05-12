<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Base\BaseFormRequest;

class PatientRegisterRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //__return user rules__
        $userRules = (new UserRegisterRequest())->rules();

        //___add patient rules___
        return array_merge($userRules, [
            'medical_info' => 'nullable|string',
        ]);
    }
}
