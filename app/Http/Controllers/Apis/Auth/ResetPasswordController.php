<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword\UpdateNewPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Password;
use App\Traits\ApiResponce;

class ResetPasswordController extends Controller
{
    use ApiResponce;

    public function SendResetLink(SendResetLinkRequest $request)
    {

        //___validate___
        $data = $request->validated();


        //___send email___
        $status = Password::sendResetLink($data['email']);


        if ($status === Password::RESET_LINK_SENT) {
            return $this->Data([], 'Reset password link sent successfully', 200);
        } else {
            return $this->ErrorMessage(
                ['error' => 'Failed to send reset link'],
                'Failed to send reset link',
                500
            );
        }

    }

    public function updateNewPassword(UpdateNewPasswordRequest $request)
    {

        //___validate___
        $data = $request->validated()->only('email', 'password', 'token');

        //__anonymous function to save new password__
        $saveingNewPassword = function () use ($data) {
            //return user
            $user = User::where("email", $data['email'])->first();
            //save password
            $user->password = Hash::make($data['password']);
            $user->save();
        };

        //___check data and store
        $status = Password::reset($data, $saveingNewPassword);

        if ($status === Password::PASSWORD_RESET) {
            return $this->SuccessMessage('Password has been reset successfully');

        } else {
            return $this->ErrorMessage(
                ['reset_failed' => __($status)],
                'Failed to reset password',
                422
            );
        }

    }
}

