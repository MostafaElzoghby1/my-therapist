<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\DoctorsAppointmentResource;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorsAppointment;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorsAppointmentController extends Controller
{
    public function all(){ //doctor all and patient show authorized
        $appointments = DoctorsAppointment::all();
    
        return DoctorsAppointmentResource::collection($appointments);
    }
    
    public function show($id){ //doctor and patient
        $appointment = DoctorsAppointment::findOrFail($id);
    
        return DoctorsAppointmentResource::make($appointment);
    }
    
    public function add(Request $request){ //doctor and check in main appointments table
        $validate = Validator::make($request->all(), [
            "day" =>"required|in:sat,sun,mon,tue,wed,thu,fri",
            "month" => "required|in:jan,feb,mar,abr,may,jun,jul,aug,sep,oct,nov,dec"
        ]);
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
        $appointment = Appointment::findOrFail($request->appointment_id);
        $drUserId = Auth::guard('sanctum')->user()->id;
        $doctor_id = Doctor::where('user_id', $drUserId)->first()->id;

        DoctorsAppointment::create([
            "doctor_id" => $doctor_id,
            "appointment_id" => $request->appointment_id,
            "day" => $request->day,
            "month" => $request->month
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    }
    
    public function update(Request $request){ //doctor where not reserved
        $validate = Validator::make($request->all(), [
            "day" =>"required|in:sat,sun,mon,tue,wed,thu,fri",
            "month" => "required|string",
            "status" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $appointment = DoctorsAppointment::where("id", $request->drAppointmentId)->first();
        if ($appointment !== null) {
            $appointment->update([
                "status" => $request->status,
                "day" => $request->day,
                "month" => $request->month
            ]);
            return response()->json([
                "msg" => "updated successfully",
            ], 200);
        } else {
            return response()->json([
                "msg" => "not found",
            ], 404);
        }
    }
    
    public function delete($drAppointmentId){ //doctor
        $appointment = DoctorsAppointment::where("id", $drAppointmentId)->first();
        if ($appointment !== null) {
            $appointment->delete();
            return response()->json([
                "msg" => "deleted successfully",
            ], 200);
        } else {
            return response()->json([
                "msg" => "not found",
            ], 404);
        }
    }
    
}
