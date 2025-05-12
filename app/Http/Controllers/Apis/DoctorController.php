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

    public function update(Request $request , $id){ //doctor 
        //admin some
        $validate =   Validator::make($request->all(),[
                  "specially"=>"string|required",
                  "activity_status"=>"bool|required",
                  "profile_image"=>"required|mimes:jpg;jpeg,png",
                  "licence_status"=>"string|required",
                  "code"=>"required"
              ]);
              
              if($validate->fails()){
                  return response()->json([
                      "error" =>$validate->errors(),
                    ],301);
                }
              $doctor =   Doctor::where("id",$id)->first();
              if($doctor !== null){
                  $image = $doctor->profile_image;
                
            }else{
                return response()->json([
                    "msg" => "not found",
            ],404);
            }
            if ($request->hasFile('profile_image')) {
                // Delete the previous image
                Storage::delete($image);
            
                // Store the new image
                $image = Storage::putFile("doctors_profiles/images", $request->file('profile_image'));
            }
            
                $doctor->update([
                  "specially"=>$request->specially,
                  "activity_status"=>$request->activity_status,
                  "profile_image"=>$image,
                  "rate"=>$request->rate,
              ]);
      
              return response()->json([
                  "msg" => "updated successfully",
          ],200);
      
          }
          

    public function delete($id){ //doctor and admin not in all details
              $doctor =   Doctor::where("id",$id)->first();
              if($doctor !== null){
                $doctor->delete();
                Storage::delete($doctor->profile_image);
      
              return response()->json([
                  "msg" => "deleted successfully",
          ],200);
              }else{
                return response()->json([
                    "msg" => "doctor not found",
            ],404);
              }
          }
          
   
}