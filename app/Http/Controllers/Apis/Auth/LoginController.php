<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Traits\ApiResponce;
use App\Traits\Registeration\AddUser;


class LoginController extends Controller
{
    use ApiResponce , AddUser;

    public function login(LoginRequest $request)
    {
        // dd($request);

        //___Validation___
        $data = $request->validated();

        //___Check if user exists___
        $user = User::where("email", $data['email'])->first();
        if ($user === null) {
            return $this->ErrorMessage(['user_not_found' => 'User not found'],
             'User not found',
              404);
        }

        //___Get and Check password___
        $hashedPasswerd = $user->password;
        if (!Hash::check($data['->password'], $hashedPasswerd)) {
            return $this->ErrorMessage(['password_incorrect' => 'Password is incorrect'], 'Password mismatch', 401);
        }

        //___Check if user is blocked___
        if ($user->status != 1) {
            return $this->ErrorMessage(
                ['account_blocked' => 'Your account is blocked or inactive'],
                'Account inactive',
                403
            );
        }

        //___Generate token___
        $rememberToken =  $this-> createRememberToken($user,$data['device_name']);

        //___get profile____
        switch ($user->type) {
            case 'patient':
                $profile = Patient::find($user->id); // Retrieve patient data (if exists)
                break;

            case 'doctor':
                $profile = Doctor::find($user->id); // Retrieve patient data (if exists)
                break;
        }
        $profile = Patient::find($user->id); // Retrieve patient data (if exists)
        //___Return success response___
        if($profile){

            return $this->Data([
                "user" => $user,
                "profile" => $profile,
                "remember_token" => $rememberToken
            ], "Authenticated successfully", 200);

        }else{
            return $this->ErrorMessage([
                'user_not_found' => 'User not found'],
             'User not found',
              404);
        }

    }



    public function logout(Request $request)
    {
        $user = Auth::guard('sanctum')->user()->logout();

        $user->currentAccessToken()->delete();

        return response()->json('loggedout successfully', 200);
    }
}
