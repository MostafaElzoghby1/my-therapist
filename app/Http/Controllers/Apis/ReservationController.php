<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Question;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{

  //doctor show his now reservations , history , reservations that wait report
  public function all()
  {

    $questions = Reservation::all();

    return ReservationResource::collection($questions);
  }

  //details about one reservation
  public function show($id)
  {
    $question = Reservation::findOrFail($id);

    return ReservationResource::make($question);
  }

  //search doctor method => give all active doctors with active appointments


  //remove from history
  //delete permenant by admin only
  public function delete($id)
  {
    $reservation = Reservation::where("id", $id)->first();
    $status = $reservation->status;
    if ($reservation !== null) { //exist 

      if ($reservation->status == 3) {// completed
        $reservation->delete();
        return response()->json([
          "msg" => "deleted successfully",
        ], 200);

      } else {
        return response()->json([
          "msg" => "cant",
        ], 200);
      }

    } else {
      return response()->json([
        "msg" => " not found",
      ], 404);
    }
  }
}
