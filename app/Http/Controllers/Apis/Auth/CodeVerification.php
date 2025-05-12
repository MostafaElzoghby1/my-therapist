<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
use App\Mail\VerificationCode;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Mail;
use Validator;
use App\Traits\Registeration\AddUser;
use App\Traits\ApiResponce;

class CodeVerification extends Controller
{
    use AddUser, ApiResponce ;

    public function sendCode()
    {
        $authenticatedUser = Auth::guard(name: 'sanctum')->user();

        //___Retrieve user____
        $user = User::find($authenticatedUser->id);

        //___create verification code___
        $verificationCode = random_int(1000, 9999);

        //___determine expired time___
        $codeExpiredAt = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        //___save data___
        $updated = $user->update([
            'verification_code' => $verificationCode,
            'code_expired_at' => $codeExpiredAt,
        ]);

        //___send code___ 
        Mail::to($user->email)->send(new VerificationCode($verificationCode));

        if ($updated) {
            return $this->SuccessMessage('Verification code sent successfully');
        }

        return $this->ErrorMessage(
            ['user_update_failed' => 'Failed to update user verification data'],
            'Error sending verification code',
            500
        );
    }

    public function checkCode(CheckCodeRequest  $request)
    {
        //___Validation___
        $data = $request->validated();


        //___get auth user___
        $authenticatedUser = Auth::guard(name: 'sanctum')->user();
        if (!$authenticatedUser) {
            return $this->ErrorMessage(
                ['authentication_error' => 'Unauthenticated user'],
                'User not authenticated',
                401
            );
        }

        //___Retrieve user____
        $user = User::find($authenticatedUser->id);

        //___check code___
        if ($user->verification_code == $data['verification_code'] && $user->code_expired_at > date('Y-m-d H:i:s')) {
            //___Account activation_____
            switch ($user->type) {
                case 'patient':
                    $userStatus = 1; // active
                    break;
                case 'doctor':
                    $userStatus = 2; // wait licence verification (inactive)
                    break;
                default:
                    $userStatus = 0; // inactive (fallback)
                    break;
            }

            //___save data___
            $updated = $this->updateVerificationInfo($user, $userStatus);

            if ($updated) {
                // Get remember token
                $rememberToken = $request->header('Authorization');

                return $this->Data(
                    [
                        'user' => $user,
                        'remember_token' => $rememberToken,
                    ],
                    'Verification successful',
                    200
                );
            } else {
                return $this->ErrorMessage(
                    ['update_error' => 'Failed to update user verification status'],
                    'Error updating verification status',
                    500
                );
            }
        } else {
            return $this->ErrorMessage(
                ['code_invalid' => 'Verification code is invalid or expired'],
                'Invalid or expired verification code',
                422
            );
        }
    }
}


