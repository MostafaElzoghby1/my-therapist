<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Base\BaseFormRequest;

class DoctorRegisterRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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

        //___add doctor rules___
        return array_merge($userRules, [
            "specially" => "required|string",
            "image" => "required|mimes:jpg;jpeg,png",
            "professional_licence" => "required|mimes:jpg;jpeg,png",
            "speciality_certificate" => "mimes:jpg;jpeg,png"
        ]);
        return [
            
        ];
    }
}
