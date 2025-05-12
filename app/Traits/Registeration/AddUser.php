<?php

namespace App\Traits\Registeration;

use App\Models\User;
use Auth;

trait AddUser
{

    public function createUser(array $data)
    {
        return User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => $data['password'],
            "birth_date" => $data['birth_date'],
            "gender" => $data['gender'],
            "phone" => $data['phone'],
            "last_name" => $data['last_name'],
            "verification_code" => $data['verification_code'],
            "type" => $data['type'],
            "device_name" => $data['device_name'],
        ]);
    }

    public function updateUser(User $user, array $data)
    {

        // $user->update([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'birth_date' => $data['birth_date'],
        //     'gender' => $data['gender'],
        //     'phone' => $data['phone'],
        //     'last_name' => $data['last_name'],
        //     'device_name' => $data['device_name'],
        // ]);
        
        return $user->update($data);

    }
    public function updateVerificationInfo($user, $userStatus)
    {
        $user->update([
            'email_verified_at' => date('Y-m-d H:i:s'), //now
            'status' => $userStatus,
            'verification_code' => null,
            'code_expired_at' => null,
        ]);

        if ($user) {
            return true;
        }
    }



    public function deleteUser(User $user)
    {
        return User::where('id', $user->id)->delete();
    }

    public function createRememberToken($user, $deviceName)
    {
        return "Bearer " . $user->createToken($deviceName)->plainTextToken;
    }

    public function getAuthUser() {
       return Auth::guard( 'sanctum')->user();
    }
}

