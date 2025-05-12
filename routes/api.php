<?php

use App\Http\Controllers\Apis\AnswerController;
use App\Http\Controllers\Apis\Auth\CodeVerification;
use App\Http\Controllers\Apis\Auth\LoginController;
use App\Http\Controllers\Apis\Auth\Registeration\AddDoctor;
use App\Http\Controllers\Apis\Auth\Registeration\DoctorRegisterController;
use App\Http\Controllers\Apis\Auth\Registeration\EmailVerification;
use App\Http\Controllers\Apis\Auth\Registeration\PatientRegisterController;
use App\Http\Controllers\Apis\Auth\Registeration\UserRegister;
use App\Http\Controllers\Apis\Chat\ChatMessageController;
use App\Http\Controllers\Apis\DiaryController;
use App\Http\Controllers\Apis\DoctorsAppointmentController;
use App\Http\Controllers\Apis\Chat\ChatController;
use App\Http\Controllers\Apis\PatientController;
use App\Http\Controllers\Apis\PaymentMethodController;
use App\Http\Controllers\Apis\Profile\MainProfileInfo;
use App\Http\Controllers\Apis\ProfileController;
use App\Http\Controllers\Apis\QuestionController;
use App\Http\Controllers\Apis\ReportController;
use App\Http\Controllers\Apis\RequiredTestController;
use App\Http\Controllers\Apis\Reservation\ConfirmReservationController;
use App\Http\Controllers\Apis\Reservation\CreateController;
use App\Http\Controllers\Apis\AppointmentController;
use App\Http\Controllers\Apis\Reservation\EndByDoctorController;
use App\Http\Controllers\Apis\Reservation\EndByPatientController;
use App\Http\Controllers\Apis\ReviewController;
use App\Http\Controllers\PaymenttestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ---------------------- AUTHENTECATION ----------------------
Route::prefix("Auth")->group(function () {
    //__registeration__

    Route::prefix("Registeration")->group(function () {

        Route::post("doctor", DoctorRegisterController::class);
        Route::post("patient", PatientRegisterController::class);
        });

    //__verification code__
    Route::controller(CodeVerification::class)->group(function () {

        Route::post('send', 'sendCode');
        Route::post('check', 'checkCode');
    })->middleware('auth:sanctum')  ;

    Route::controller(LoginController::class)->group(function () {

        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
    });
});

// ---------------------- PROFILE ----------------------
Route::prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get("show", "show");
    Route::put("update", "update");
    Route::delete("delete", "delete");
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::post('update', [MainProfileInfo::class, 'updateName']);
    Route::post('change-password', [MainProfileInfo::class, 'changePassword']);
    Route::post('change-email', [MainProfileInfo::class, 'changeEmail']);
});

// ---------------------- Questions ----------------------
Route::prefix('question')->group(function () {
    Route::controller(QuestionController::class)->group(function () {
        Route::get("/", "all");
        Route::get("show/{id}", "show");
        Route::post("add", "add");
        Route::put("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
    });
})->middleware('auth:sanctum');

// ---------------------- RESERVATIONS ----------------------
Route::prefix('reservation')->group(function () {
    Route::prefix('create')->controller(CreateController::class)->group(function () {
        Route::get("profile/{doctorId}", "showProfile");
        Route::get("appointments/{doctorId}", "showAppointments");
        Route::get("paymentMethod", "returnPaymentMethod");
        Route::put("reservation/update/{id}", "update");
        Route::get("reservation", "all");
        Route::get("reservation/show/{patientId}/{reservationId}", "show");

    });
    Route::post("confirm/{drUserId}/{appointmentId}", ConfirmReservationController::class);
    Route::post("endByPatient/{reservationId}", EndByPatientController::class);
    Route::post("endByDoctor/{reservationId}", EndByDoctorController::class);

})->middleware('auth:sanctum');

// ---------------------- DOCTOR APPOINTMENTS ----------------------
Route::prefix('doctor/appointment')->controller(DoctorsAppointmentController::class)->group(function () {
    Route::get("/", "all"); // GET /doctor/appointment
    Route::get("show/{id}", "show");
    Route::post("add", "add");
    Route::put("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
})->middleware('auth:sanctum');

// ---------------------- PAYMENT METHODS ----------------------
Route::prefix('payment-method')->controller(PaymentMethodController::class)->group(function () {
    Route::get("/", "all");
    Route::get("show/{id}", "show");
    Route::post("add", "add");
    Route::delete("delete/{id}", "delete");
})->middleware('auth:sanctum');

// ---------------------- CHAT ----------------------
Route::prefix('chat')->controller(ChatController::class)->group(function () {
    Route::get("my-chats", "myChats");
    Route::get("get-chat-by-user/{userId}", "getChatByUser");
    Route::get("get-chat-by-id/{id}", "getChatById");
})->middleware('auth:sanctum');

Route::prefix('chat-message')->controller(ChatMessageController::class)->group(function () {
    Route::post("send/{chat_id}", "send");
    Route::get("get/{chat_id}", "get");
})->middleware('auth:sanctum');

// ---------------------- DIARIES ----------------------
Route::prefix('patient')->controller(PatientController::class)->group(function () {
    Route::get("doctor/{doctorId}", "all");   
    Route::get("show/{id}", "show");         
    Route::post("add", "add");               
    Route::put("update", "update");          
    Route::delete("delete", "delete");       
})->middleware('auth:sanctum');


// ---------------------- DIARIES ----------------------
Route::prefix('diary')->controller(DiaryController::class)->group(function () {
    Route::get("/", "all");
    Route::get("show/{id}", "show");      
    Route::post("add", "add");              
    Route::put("update/{id}", "update");     
    Route::delete("delete/{id}", "delete");  
})->middleware('auth:sanctum');

// ---------------------- APPOINTMENTS ----------------------
Route::prefix('appointment')->controller(AppointmentController::class)->group(function () {
    Route::get("/", "all");
    Route::get("show/{id}", "show");
})->middleware('auth:sanctum');

// ---------------------- REPORTS ----------------------
Route::prefix('report')->controller(ReportController::class)->group(function () {
    Route::get("/", "all");
    Route::get("show/{id}", "show");
    Route::post("add", "add");
    Route::put("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
})->middleware('auth:sanctum');

// ---------------------- REQUIRED TESTS ----------------------
Route::prefix('test')->controller(RequiredTestController::class)->group(function () {
    Route::get("/", "all"); // GET /test
    Route::get("show/{id}", "show");
    Route::post("add", "add");
    Route::put("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
})->middleware('auth:sanctum');

// ---------------------- REVIEWS ----------------------
Route::prefix('review')->controller(ReviewController::class)->group(function () {
    Route::get("/", "all");
    Route::get("show/{id}", "show");
    Route::post("add", "add");
    Route::put("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
})->middleware('auth:sanctum');

// ---------------------- ANSWERS ----------------------
Route::prefix('answer')->controller(AnswerController::class)->group(function () {
    Route::get("/", "all");
    Route::get("show/{id}", "show");
    Route::post("add", "add");
    Route::put("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
})->middleware('auth:sanctum');


// ---------------------- TEST ----------------------
// Route::post('payments', [PaymenttestController::class, 'stripePost']); for test

