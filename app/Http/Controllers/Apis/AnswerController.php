<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function all($reservationId){//doctor show for specific patient
      //patient show his reservations
        $answer =  Answer::where('reservation_id_id',$reservationId)->get();
        ;
    
        return AnswerResource::collection($answer);
        }
    
        public function show($id){
            $question =     Answer::findOrFail($id);
        
            return AnswerResource::make($question);
            }
        public function add(Request $request){ //patient inner reservation
                $validate =   Validator::make($request->all(),[
                      "reservation_id"=>"required",
                      "answer"=>"required|string",
                      "question_id"=>"required",
                  ]);
    
          
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                      ],301);
                  }
                  Answer::create([
                    "reservation_id"=>$request->reservation_id,
                    "answer"=>$request->answer,
                    "question_id"=>$request->question_id,
                  ]);
          
                  return response()->json([
                      "msg" => "added successfully",
              ],200);
          
              }
    
        public function update(Request $request , $id){ //patient inner reservation
            $validate =   Validator::make($request->all(),[
                "reservation_id"=>"required",
                "answer"=>"required|string",
                "question_id"=>"required",
                  ]);
                  
                  if($validate->fails()){
                      return response()->json([
                          "error" =>$validate->errors(),
                        ],301);
                    }
                  $answer =   Answer::where("id",$id)->first();
                  if($answer !== null){

                    $answer->update([
                        "reservation_id"=>$request->reservation_id,
                        "answer"=>$request->answer,
                        "question_id"=>$request->question_id,
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
                $answer =   Answer::where("id",$id)->first();
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
