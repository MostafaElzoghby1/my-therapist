<?php
//patient use it to show and treat
//doctor to auth or edit
namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
  
    public function all(){ //patient get authorizeds for search
    $doctors = Doctor::where('status', '1')->get();

    return DoctorResource::collection($doctors);
    }

    public function show($id){ //patient and doctor show profile 
        //patient show reviews also
        $doctor =     Doctor::findOrFail($id);
    
        return DoctorResource::make($doctor);
        }
          
   
}
