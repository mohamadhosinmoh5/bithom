<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Route::prefix('v1')->group(function () {

// });


Route::post('/auth', [AuthController::class, 'checkPhone']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::post('/auth/generateOtp', [AuthController::class, 'generateRandomOTP']);
Route::post('/auth/checkOtp', [AuthController::class, 'checkOtp']);


Route::post('/auth/register', [AuthController::class, 'createUser']);

