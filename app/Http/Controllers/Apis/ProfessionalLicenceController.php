<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\ProfessionalLicenceResource;
use App\Models\Professional_licence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfessionalLicenceController extends Controller
{
    public function show($id){
        $licence = Professional_licence::findOrFail($id);
    
        return ProfessionalLicenceResource::make($licence);
    }
    
   
    public function update(Request $request , $id){
                $validate =   Validator::make($request->all(),[
                    "doctor_id"=>"required",
                    "professional_licence"=>"required|mimes:jpg;jpeg,png",
                    "speciality_certificate"=>"mimes:jpg;jpeg,png"
                  ]);
                  
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                        ],301);
                    }
                  $doctor =   Professional_licence::find($id);
                  if($doctor !== null){
                      $profession = $doctor->syndicate_card;
                      $specialize = $doctor->speciality_certificate;
                    
                }else{
                    return response()->json([
                        "msg" => "not found",
                ],404);
                }
                if ($request->hasFile('professional_licence')) {
                    // Delete the previous image
                    Storage::delete($profession);
                
                    // Store the new image
                    $profession =    Storage::putFile("images",$request->professional_licence);
                }
                if ($request->hasFile('speciality_certificate')) {
                    // Delete the previous image
                    Storage::delete($specialize);
                
                    // Store the new image
                    $specialize =    Storage::putFile("images",$request->speciality_certificate);
                }
                
                    $doctor->update([
                        "professional_licence"=>$profession,
                        "graduation_certificate"=>$specialize,
                  ]);
          
                  return response()->json([
                      "msg" => "updated successfully",
              ],200);
          
    }
    
    public function delete($id){
        $licence = Professional_licence::where("id", $id)->first();
        if ($licence !== null) {
            $licence->delete();
            Storage::delete($licence->syndicate_card);
            Storage::delete($licence->graduation_certificate);
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
