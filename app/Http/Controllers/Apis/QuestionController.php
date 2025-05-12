<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function all($doctorId){ //doctor and patient show specific
        $questions =     Question::where('doctor_id', $doctorId)->get();
    
        return QuestionResource::collection($questions);
        }
    
        public function show($id){
            $question =     Question::findOrFail($id);
        
            return QuestionResource::make($question);
            }
        public function add(Request $request){ //doctor
                $validate =   Validator::make($request->all(),[
                      "doctor_id"=>"required",
                      "question"=>"required|string",
                  ]);
    
          
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                      ],301);
                  }
                  Question::create([
                      "doctor_id"=>$request->doctor_id,
                      "question"=>$request->question,
                  ]);
          
                  return response()->json([
                      "msg" => "added successfully",
              ],200);
          
              }
    
        public function update(Request $request , $id){ //doctor
            $validate =   Validator::make($request->all(),[
                "doctor_id"=>"required",
                "question"=>"required|string",
                  ]);
                  
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                        ],301);
                    }
                  $question =   question::where("id",$id)->first();
                  if($question !== null){

                    $question->update([
                        "doctor_id"=>$request->doctor_id,
                        "question"=>$request->question,
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
              
    
              public function delete($id){ //doctor
                $question =   Question::where("id",$id)->first();
                if($question !== null){
                  $question->delete();
        
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
