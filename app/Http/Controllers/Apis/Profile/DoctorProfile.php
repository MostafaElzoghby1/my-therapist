<?php

namespace App\Http\Controllers\Apis\Profile;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Traits\Registeration\AddUser;
use App\Traits\ApiResponce;
use Storage;

class DoctorProfile extends Controller
{
    use ApiResponce , AddUser;
    // show my profile
    public function show()
    {
        //__get authed__
        $authUser = $this->getAuthUser();

        //__get doctor___
        $doctor = Doctor::find($authUser->id);

        //__return__
        if (!$doctor) {
            return $this->ErrorMessage([
                'not_found' => 'Doctor profile not found',
            ], 'Doctor not found', 404);
        }
        return $this->Data($doctor, "Doctor profile data");
    }


    //update

     // Update profile image
     public function updateProfileImage(UpdateProfileImageRequest $request)
     {
         $authUser = $this->getAuthUser();
         $doctor = Doctor::find($authUser->id);
 
         if (!$doctor) {
             return $this->ErrorMessage(['not_found' => 'Doctor not found'], 'Not found', 404);
         }
 
         // Delete old image if exists
         if ($doctor->profile_image) {
             Storage::disk('public')->delete($doctor->profile_image);
         }
 
         $path = $request->file('profile_image')->store('doctors/profile_images', 'public');
 
         $doctor->update([
             'profile_image' => $path,
         ]);

         return $this->SuccessMessage('Profile image updated successfully');
    }

    //activity status
    public function updateActivityStatus(UpdateActivityStatusRequest $request)
    {
        $authUser = $this->getAuthUser();
        $doctor = Doctor::find($authUser->id);

        if (!$doctor) {
            return $this->ErrorMessage(['not_found' => 'Doctor not found'], 'Not found', 404);
        }

        $doctor->update([
            'activity_status' => $request->activity_status,
        ]);

        return $this->SuccessMessage('Activity status updated successfully');
    }

     // Update connection status
     public function updateConnectionStatus(UpdateConnectionStatusRequest $request)
     {
         $authUser = $this->getAuthUser();
         $doctor = Doctor::find($authUser->id);
 
         if (!$doctor) {
             return $this->ErrorMessage(['not_found' => 'Doctor not found'], 'Not found', 404);
         }
 
         $doctor->update([
             'connection_status' => $request->connection_status,
         ]);
 
         return $this->SuccessMessage('Connection status updated successfully');
     }
    
}
