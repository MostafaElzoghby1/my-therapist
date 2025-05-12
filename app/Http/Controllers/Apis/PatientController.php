<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function myPatients(){ //doctor show what have now 
        //doctor show history with case
        $doctor_id = Auth::guard('sanctum')->user()->id;
$patients = Patient::whereHas('reservations', function($query) use ($doctor_id) {
    $query->where('doctor_id', $doctor_id);
})->with(['reservations' => function($query) use ($doctor_id) {
    $query->where('doctor_id', $doctor_id);
}])->get();        return PatientResource::collection($patients);
        }
    
    public function show($id){ 
            $patient =     Patient::findOrFail($id);
        
            return PatientResource::make($patient);
            }
    
    public function update(Request $request , $id){ //patient
                $validate =   Validator::make($request->all(),[
                    "user_id"=>"",
                    "image"=>"",
                    "num_of_reservation"=>"required|string",

                  ]);
                  
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                        ],301);
                    }
                  $doctor =   Patient::where("id",$id)->first();
                  if($doctor !== null){
                      $image = $doctor->image;
                    
                }else{
                    return response()->json([
                        "msg" => "not found",
                ],404);
                }
                if ($request->hasFile('image')) {
                    // Delete the previous image
                    Storage::delete($image);
                
                    // Store the new image
                    $image = Storage::putFile("images", $request->file('image'));
                }
                
                    $doctor->update([
                        "user_id"=>$request->user_id,
                        "image"=>$image,
                        "num_of_reservation"=>$request->num_of_reservation,
                  ]);
          
                  return response()->json([
                      "msg" => "updated successfully",
              ],200);
          
    }
              
    
    public function delete($id){
               
                  $patient =   Patient::where("id",$id)->first();
                  if ($patient !==null){

                      $patient->delete();
                      Storage::delete($patient->image);
                      return response()->json([
                          "msg" => "deleted successfully",
                        ],200);
                  }else{
                    return response()->json([
                        "msg" => "patient not found",
                      ],404);
                  }
          
          
    }            
}
