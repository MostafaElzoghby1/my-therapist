<?php

namespace App\Http\Controllers\Apis\Auth\Registeration;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRegisterRequest;
use Hash;
use App\Traits\Registeration;
use App\Traits\ApiResponce;

class PatientRegisterController extends Controller
{
    use Registeration\AddDoctor;
    use Registeration\AddUser;
    use ApiResponce;

    public function __invoke(PatientRegisterRequest $request)
    {
        //___validation___
        $data = $request->validated(); // Return array
        if (!$data) {
            return $this->ErrorMessage(
                ['validation_error' => 'Invalid data'],
                'Validation failed',
                422
            );
        }

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

        //___Create patient___
        $patient = $this->createProfile($data, $user->id);
        if (!$patient) {
            return $this->ErrorMessage(
                ['patient_creation_failed' => 'Failed to create patient profile'],
                'Error creating patient profile',
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
            "Patient registered successfully",
            200
        );
    }
}
