<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function all(){ //patient show what has
        $answer =     Report::all();
    
        return ReportResource::collection($answer);
        }
    
        public function show($id){ //doctor and patient show it
            $question =     Report::findOrFail($id);
        
            return ReportResource::make($question);
            }
        public function add(Request $request){ //doctor 
                $validate =   Validator::make($request->all(),[
                    "note"=>"required|string" ,
                     "session_deuration"=>"required",
                      "reservation_id"=>"required"
                  ]);
    
          
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                      ],301);
                  }
                  Report::create([
                    "note"=>$request->note ,
                    "session_deuration"=>$request->session_deuration,
                    "reservation_id"=>$request->reservation_id
                  ]);
          
                  return response()->json([
                      "msg" => "added successfully",
              ],200);
          
              }
    
        public function update(Request $request , $id){ //doctor
            $validate =   Validator::make($request->all(),[
                "note"=>"required|string" ,
                "session_deuration"=>"required",
                 "reservation_id"=>"required"
                  ]);
                  
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                        ],301);
                    }
                  $answer =   Report::where("id",$id)->first();
                  if($answer !== null){

                    $answer->update([
                        "note"=>$request->note ,
                        "session_deuration"=>$request->session_deuration,
                        "reservation_id"=>$request->reservation_id
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
              
    
              public function delete($id){ //patient
                $answer =   Report::where("id",$id)->first();
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
