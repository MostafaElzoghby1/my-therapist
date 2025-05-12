<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Str;

class ProfileController extends Controller
{
    public function update(Request $request){

        $id = Auth::guard('sanctum')->user()->id;

        $validate =   Validator::make($request->all(),[
              "name"=>"required|string",
              "email"=>"required|string",
              "password"=>"required|string",
              "phone"=>"required",
              "last_name"=>"required",
              "status"=>"string|required",

          ]);
  
          if($validate->fails()){
              return response()->json([
                  "error" =>$validate->errors(),
              ],301);
          }
          $password = bcrypt($request->password);
          $remmeber = Str::random(64);
          $randomNumber = random_int(1000, 9999);
          User::where('id',$id)->Update([
              "name"=>$request->name,
              "email"=>$request->email,
              "password"=>$password,
              "status"=>$request->status,
              "phone"=>$request->phone,
              "last_name"=>$request->last_name,
          ]);
  
          return response()->json([
              "msg" => "registerd successfully",
              "remember_token"=>$remmeber,
              "verification" =>$randomNumber 
      ],200);
  
      }

      public function delete(){ 

        $id = Auth::guard('sanctum')->user()->id;

        $user =   User::where("id",$id)->first();
        if($user !== null){
          $user->delete();

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
