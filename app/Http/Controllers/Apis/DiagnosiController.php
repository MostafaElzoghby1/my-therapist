<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\DiagnosiResource;
use App\Models\diagnosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosiController extends Controller
{
    public function all(){ //admin or not
        $answer =     diagnosi::all();
    
        return DiagnosiResource::collection($answer);
        }
    
        public function show($id){ //doctor and patient
            $question =     diagnosi::findOrFail($id);
        
            return DiagnosiResource::make($question);
            }
            
        public function add(Request $request){ //doctor
                $validate =   Validator::make($request->all(),[
                    "disorder"=>"required" , "type"=>"required" ,
                     "degree"=>"required" , "excpected_reson"=>"required" , "report_id"=>"required"
                  ]);
    
          
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                      ],301);
                  }
                  diagnosi::create([
                    "disorder"=>$request->disorder ,
                     "type"=>$request->type,
                     "degree"=>$request->degree ,
                      "excpected_reson"=>$request->excpected_reson ,
                       "report_id"=>$request->report_id
                  ]);
          
                  return response()->json([
                      "msg" => "added successfully",
              ],200);
          
              }
    
        public function update(Request $request , $id){ //doctor inner reservation
            $validate =   Validator::make($request->all(),[
                "disorders"=>"required" , "type"=>"required" ,
                     "degree"=>"required" , "excpected_reson"=>"required" , "report_id"=>"required"
                  ]);
                  
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                        ],301);
                    }
                  $answer =   diagnosi::where("id",$id)->first();
                  if($answer !== null){

                    $answer->update([
                        "disorders"=>$request->disorders ,
                        "type"=>$request->type,
                        "degree"=>$request->degree ,
                         "excpected_reson"=>$request->excpected_reson ,
                          "report_id"=>$request->report_id
                    ]);
            
                    return response()->json([
                        "msg" => "updated successfully",
                ],200);
                    
                }else{
                    return response()->json([
                        "msg" => "not found",
                ],404);
                }
                
          
              }
              
    
              public function delete($id){ //doctor inner reservation
                $answer =   diagnosi::where("id",$id)->first();
                if($answer !== null){
                  $answer->delete();
        
                return response()->json([
                    "msg" => "deleted successfully",
            ],200);
                }else{
                  return response()->json([
                      "msg" => " not found",
              ],404);
                }
            }
}
