
<?php

namespace App\Http\Controllers\Apis\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePersonalRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\ChangeEmailRequest;
use App\Traits\ApiResponce;
use App\Traits\Registeration\AddUser;

class MainProfileInfo extends Controller
{
    use ApiResponce, AddUser;

    //  Update name, phone, birthdate

public function updatePersonalInfo(UpdatePersonalRequest $request)
{
    //___validate__
    $data = $request->validated();

    //__get auth___
    $user = $this->getAuthUser();

    //__update___
    $updated = $user->update($data);

    if (!$updated) {
        return $this->ErrorMessage([
            'update_failed' => 'Failed to update profile'
        ], 'Update failed', 500);
    }

    return $this->SuccessMessage('Profile updated successfully');
}


public function changeEmail(ChangeEmailRequest $request)
{
    //___validate__
    $data = $request->validated();

    //__get auth___
    $user = $this->getAuthUser();

    //__update__
    $updated = $user->update([
        'email' => $data['email'],
        'status' => 0 // block until verification
    ]);

    if (!$updated) {
        return $this->ErrorMessage([
            'email_update_failed' => 'Failed to change email'
        ], 'Email update failed', 500);
    }

    return $this->SuccessMessage('Email changed successfully');
}

    // Change password
    public function changePassword(ChangePasswordRequest $request)
    {
        //___validate__
        $data = $request->validated();

        //__get auth___
        $user = $this->getAuthUser();

        if (!Hash::check($data['old_password'], $user->password)) {
            return $this->ErrorMessage([
                'old_password' => 'Old password is incorrect'
            ], 'Password mismatch', 401);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return $this->SuccessMessage('Password changed successfully');
    }

}
