<?php
//controle by admin
namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function all(){
        $answer = Appointment::all();
    
        return AppointmentResource::collection($answer);
    }
    
    public function show($id){
        $appointment = Appointment::findOrFail($id);
    
        return AppointmentResource::make($appointment);
    }
    
}
