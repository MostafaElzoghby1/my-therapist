<?php

namespace App\Http\Controllers\Apis\Auth\Registeration;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCode;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use Validator;

class UserRegister extends Controller
{
    public function __invoke(Request $request)
  {
    $validate = Validator::make($request->all(), [
      "name" => "required|string",
      "email" => "required|string",
      "password" => "required|string",
      "birth_date" => "required",
      "gender" => "required",
      "phone" => "required",
      "last_name" => "required",
      "type" => "required|string|in:doctor,patient",
      "device_name" => "required"
    ]);
    //   dd($request);
    if ($validate->fails()) {
      return response()->json([
        "error" => $validate->errors(),
      ], 301);
    }
    $password = bcrypt(value: $request->password);
    $verification_code = random_int(min: 1000, max: 9999);

    //send mail 
    Mail::to($request->email)->send(new VerificationCode($verification_code));

    $user = User::create([
      "name" => $request->name,
      "email" => $request->email,
      "password" => $password,
      "birth_date" => $request->birth_date,
      "gender" => $request->gender,
      "phone" => $request->phone,
      "last_name" => $request->last_name,
      "verification_code" => $verification_code,
      "type" => $request->type,
      "device_name" => $request->device_name
    ]);

    if($user){
        $user->remember_token = "Bearer " . $user->createToken($request->device_name)->plainTextToken;
    
        return response()->json([
          "remember_token" => $user->remember_token,
          "msg" => "registerd successfully",
          "verification_code" => $verification_code
        ], 201);
    }
  }

}
