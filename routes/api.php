<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\fileUploadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\WalletController;

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
Route::post('/auth/checkOtp', [AuthController::class, 'checkOtp']);


Route::post('/auth/generateOtp', [AuthController::class, 'generateRandomOTP']);
Route::post('/auth/updateOtp', [AuthController::class, 'updateOtp']);


Route::post('/auth/register', [AuthController::class, 'createUser']);

Route::post('/userPanel/changePassword', [UserController::class, 'chengePassword']);
Route::get('/userPanel/userInfo', [UserController::class, 'getUserInfo']);
Route::post('/userPanel/userUpdate', [UserController::class, 'userUpdate']);

Route::get('/userPanel/userFile', [UserController::class, 'getUserFile']);


Route::post('/userPanel/auth', [FileUploadController::class, 'UploadFiles']);

Route::get('/userPanel/getTicket', [TicketController::class, 'getTickets']);
Route::get('/userPanel/getMessage', [TicketController::class, 'getTicket']);

Route::post('/userPanel/createMessage', [TicketController::class, 'createMessage']);

Route::post('/userPanel/createAnswer', [TicketController::class, 'createAnswer']);

// Route::post('/userPanel/messagAnswer', [TicketController::class, 'messageAnswer']);

Route::get('/getProjects', [ProjectController::class, 'getProjects']);
Route::get('/getProject', [ProjectController::class, 'getProject']);
Route::post('/getBuyIformation', [BuyController::class, 'getBuyIformation']);


Route::get('/getWallet', [WalletController::class, 'getWallet']);
Route::get('/getTransactions', [WalletController::class, 'getTransactions']);

Route::get('/getMyAssets', [AssetManagementController::class, 'myAssets']);



Route::post('/payment', [PaymentController::class, 'payment']);

Route::post('/buy', [BuyController::class, 'buy']);


Route::post('/callbackUrl', [PaymentController::class, 'callbackUrl']);
Route::get('/callbackUrl', [PaymentController::class, 'callbackUrl']);






















