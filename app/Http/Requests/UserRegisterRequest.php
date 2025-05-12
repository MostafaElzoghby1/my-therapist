<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Base\BaseFormRequest;

class UserRegisterRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "required|string",
            "email" => "required|string",
            "password" => "required|string",
            "birth_date" => "required",
            "gender" => "required|in:male,female",
            "phone" => "required",
            "last_name" => "required",
            "type" => "required|string|in:doctor,patient",
            "device_name" => "required"
        ];
    }
}
