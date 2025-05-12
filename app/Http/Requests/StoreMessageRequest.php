<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Illuminate\Foundation\Http\FormRequest;
use Request;
use App\Http\Requests\Base\BaseFormRequest;

class StoreMessageRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $chatId = $this->route('chat_id');
        $chat = Chat::where('id' , $chatId)->first();
        if($chat->status == 1){
            return true;
        }else {
            return false;
        }   
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'message'=>'required|string',
        ];
    }
}