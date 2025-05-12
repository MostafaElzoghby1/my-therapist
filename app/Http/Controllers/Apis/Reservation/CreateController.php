<?php

namespace App\Http\Controllers\Apis\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\DoctorsAppointmentResource;
use App\Http\Resources\PaymentMethodResource;
use App\Models\Credit_card;
use App\Models\Doctor;
use App\Models\Doctors_appointment;
use App\Models\Electronic_wallet;
use App\Models\payment_method;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateController extends Controller
{
    //return profile
    //return apps
    //return patient payments(paymentMethods and info)
    public function showProfile($id)
    {
        $doctor = Doctor::findOrFail($id);

        return DoctorResource::make($doctor);
    }

    public function showAppointments($doctorId)
    { //doctor all and patient show authorized

        $appointments = DB::table('doctors_appointments')
            ->join('appointments', 'doctors_appointments.appointment_id', '=', 'appointments.id')
            ->select('doctors_appointments.id', 'doctors_appointments.doctor_id', 'doctors_appointments.appointment_id', 'doctors_appointments.status', 'doctors_appointments.day', 'doctors_appointments.month', 'appointments.starts_at', 'appointments.ends_at')
            ->where('doctors_appointments.status', 1)
            ->where('doctor_id', $doctorId)
            ->get();
        return response()->json($appointments);
    }
    public function returnPaymentMethod()
    {

        $userId = Auth::guard(name: 'sanctum')->user()->id;

        $method = Pa::where('user_id', $userId)->first();
        if ($method->type = "credit_card") {
            $info = Credit_card::where('payment_method_id', $method->id)->first();
        } elseif($method->type = "electronic_wallet") {
            $info = Electronic_wallet::where('payment_method_id', $method->id)->first();

        }
        $paymentMethod = array_merge($method->toArray(), $info->toArray());
        return response()->json($paymentMethod);

    }


}
