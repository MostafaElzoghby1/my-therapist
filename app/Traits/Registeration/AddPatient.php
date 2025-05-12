<?php
namespace App\Traits\Registeration;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use Storage;
use Str;
use Validator;

class AddPatient extends Controller
{
    public function createProfile($requestData,$userId)
    {

        //__store personal images__(unnecessary)
        if($requestData['image']){
            $imageName = $requestData['image'];

            $imageLocation = Storage::putFile("images/patients/patients-profiles", $imageName );
        }

        //----create patient table----
        $patient = Patient::create([
            "user_id" => $userId,
            "image" => $imageLocation,
            "medical_info" => $requestData['medical_info']
        ]);

        if ($patient) {
            return $patient;
        }

    }
}