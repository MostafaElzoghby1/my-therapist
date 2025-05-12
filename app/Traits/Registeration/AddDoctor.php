<?php

namespace App\Traits\Registeration;

use App\Models\Doctor;
use App\Models\ProfessionalLicence;
use App\Models\User;
use Storage;
use Str;
use App\Traits\Stripeservice;


trait AddDoctor
{
    use Stripeservice;

    public function createProfile($requestData , $userId)
    {

        //---create connected account for stripe payments---.
        $connectedAccount = $this->addDoctorAccount($requestData['email']);


        //__store personal images__
        $profileImage = Storage::putFile("images/doctors/doctors-profiles", $requestData['image']);
        $verificationImage = Storage::putFile("images/doctors/doctors-verification", $requestData['image']);


        //----code for search inner application----
        $uuid = Str::uuid()->toString();
        $code = intval(preg_replace('/[^0-9]/', '', $uuid)); //unique identifier doctor code 


        //----create doctor table----
        $doctor = Doctor::create([
            "user_id" => $userId,
            "specially" => $requestData['specially'],
            "profile_image" => $profileImage,
            "verification_image" => $verificationImage,
            "code" => $code,
            "stripe_doctor_id" => $connectedAccount

        ]);

        if ($doctor) {
            return $doctor;
        }

    }

    public function addProfessionalLicence($requestData , $doctorId)
    {

        //__strore images__
        $professionalLicenceName = $requestData['professional_licence'];

        $professionalLicenceLocation = Storage::putFile(
            "images/doctors/professional-licence",
            $professionalLicenceName = $requestData['professional_licence']
        );//necessary


        if ($requestData['speciality_certificate']) {
            $specialityCertificateName = $requestData['speciality_certificate'];

            $specialityCertificateLocation = Storage::putFile(
                "images/doctors/specialitiy_certificates",
                $specialityCertificateName
            );//non necessary
        }

        //__create its table
        $professionalLicence = ProfessionalLicence::create([
            "doctor_id" => $doctorId,
            "professional_licence" => $professionalLicenceLocation,
            "speciality_certificate" => $specialityCertificateLocation,
        ]);

        if ($professionalLicence) {
            return true;
        }

    }
}