<?php


namespace App\Http\Controllers\Apis\Profile;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponce;
use App\Traits\Registeration\AddUser;
use App\Http\Requests\Profile\UpdateProfileImageRequest;

class PatientProfile extends Controller
{
    use ApiResponce, AddUser;

    // show my profile
    public function show()
    {
        //__get authed__
        $authUser = $this->getAuthUser();
        $patient = Patient::find($authUser->id);

        //__return__
        if (!$patient) {
            return $this->ErrorMessage([
                'not_found' => 'Patient profile not found',
            ], 'Patient not found', 404);
        }

        return $this->Data($patient, "Patient profile data");
    }

    // update profile_image
    public function updateProfileImage(UpdateProfileImageRequest $request)
    {
        $authUser = $this->getAuthUser();
        $patient = Patient::find($authUser->id);

        if (!$patient) {
            return $this->ErrorMessage(['not_found' => 'Patient not found'], 'Not found', 404);
        }

        // Delete old image if exists
        if ($patient->profile_photo) {
            Storage::disk('public')->delete($patient->profile_photo);
        }

        $path = $request->file('profile_image')->store('patients/profile_images', 'public');

        $patient->update([
            'profile_photo' => $path,
        ]);

        return $this->SuccessMessage('Profile image updated successfully');
    }

    // delete account
    public function deleteAccount()
    {
        //__get auth___
        $authUser = $this->getAuthUser();
        $patient = Patient::find($authUser->id);

        //__check active reservations__
        $hasReservations = Reservation::where('patient_id', $patient->id)
            ->where('status', 1)
            ->exists();

        if ($hasReservations) {
            return $this->ErrorMessage([
                'has_active_reservations' => 'You have active reservations and cannot delete your account'
            ], 'Cannot delete account', 403);
        }

        //__remove image__
        if ($patient->profile_photo) {
            Storage::disk('public/patients')->delete($patient->profile_photo);
        }

        //__delete records__
        DB::transaction(function () use ($authUser) {
            Patient::where('id', $authUser->id)->delete();
            User::where('id', $authUser->id)->delete();
        });

        return $this->SuccessMessage('Account deleted successfully');
    }
}
