<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;


trait ApiResponce {
    //return empty object when variable hasn`t parameter.
    //it make frontend avoid null results.
    //make all data has the same type.
    //that Unify the response form.

    public function SuccessMessage(string $message = "",int $code = 200)
    {
        return response()->json(
            [
                'message'=>$message,
                'errors'=>(object)[],
                'data'=>(object)[],
            ],
            $code
        );
    }

    public function ErrorMessage(Array $errors , string $message = "",int $code = 422)
    {
        return response()->json(
            [
                'message'=>$message,
                'errors'=> $errors,
                'data'=>(object)[],
            ],
            $code
        );
    }
    //for validation => we create a middle file between requests and parent request to override on failedValidation() 
    // it is give us uniform response for invalid request as Error message 

    
    public function Data(Array $data,string $message = "",int $code = 200)
    {
        return response()->json(
            [
                'message'=>$message,
                'errors'=>(object)[],
                'data'=>$data,
            ],
            $code
        );
    }



   
}