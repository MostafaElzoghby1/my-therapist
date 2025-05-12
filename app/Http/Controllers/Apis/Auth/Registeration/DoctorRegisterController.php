<?php

namespace App\Http\Controllers\Apis\Auth\Registeration;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRegisterRequest;
use App\Models\Doctor;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use App\Traits\Registeration;
use App\Traits\ApiResponce;

class DoctorRegisterController extends Controller
{
    use Registeration\AddDoctor;
    use Registeration\AddUser;
    use ApiResponce;

    public function __invoke(DoctorRegisterRequest $request)
    {
        //___validation___
        $data = $request->validated(); //Return array


        //___hashing pass___
        $hashedPassword = Hash::make($request->password);
        $data['password'] = $hashedPassword; // Push to data


        //___create user___
        $user = $this->createUser($data); // Handle needed data only
        if (!$user) {
            return $this->ErrorMessage(
                ['user_creation_failed' => 'Failed to create user'],
                'Error creating user',
                500
            );
        }


        //___create doctor___
        // Create profile
        $doctor = $this->createProfile($data, $user->id);
        if (!$doctor) {
            return $this->ErrorMessage(
                ['doctor_creation_failed' => 'Failed to create doctor profile'],
                'Error creating doctor profile',
                500
            );
        }


        // Add professional licence
        $Licence = $this->addProfessionalLicence($data, $doctor->id);
        if (!$Licence) {
            return $this->ErrorMessage(
                ['licence_error' => 'Failed to add professional licence'],
                'Error with licence verification',
                500
            );
        }


        //___create token___
        $rememberToken = $this->createRememberToken($user, $data['device_name']);
        if (!$rememberToken) {
            return $this->ErrorMessage(
                ['token_error' => 'Failed to generate token'],
                'Error generating token',
                500
            );
        }


        
        //___return data with token___
        return $this->Data(
            [
                "warn" => "wait for licence-verification",
                "remember_taken" => $rememberToken
            ],
            " Registered successfully",
            200
        );
    }
}
