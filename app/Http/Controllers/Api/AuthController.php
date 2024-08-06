<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\BackgroundTask;
use Illuminate\Http\Request;
use App\Models\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors());
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        if(User::where('mobile', $request->mobile)->exists())
            return response()->json([
                'status' => true,
            ], 200);
        else{
            // $this->generateRandomOTP();
            BackgroundTask::dispatch($request->mobile)->delay(Carbon::now()->addMinutes(1));
            return response()->json([
                'status' => false,
            ], 200);
        }
    }

    public function updateOtp(Request $request)
    {
        $validator = validator::make($request->all(), [
            'mobile' => 'required|numeric',
        ]);

        if($validator->failed())
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors(),
            ], 400);

        $user = User::where('mobile', $request->mobile)->first();
        if($user){
            $user->otp = random_int(1000, 9999);
            $user->save();

        }else{
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }


        return response()->json([
            'status' => true,
            'message' => 'رمز ارسال شد.',
        ], 201);

    }

    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
            'password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/\d/',
            ],

        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        if (!Auth::attempt($request->only(['mobile', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $user = User::where('mobile', $request->mobile)->first();

        $token = $user->createToken('API Token')->plainTextToken;
        $user -> remember_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'token' => $token,
            'name' => $user->name,
            'family' => $user->family,
            'mobile' => $user->mobile,
        ], 200);
    }

    function generateRandomOTP($mobile)
    {
        $user = new User;
        $user->mobile = $mobile;
        $user->otp = random_int(1000, 9999);
        $user->save();
        return true;
    }

    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
            'otp' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($user = User::where(['mobile' => $request->mobile,'otp' => $request->otp])->first())
            return response()->json([
                'status' => true,
                'message' => 'Correct OTP',
                'name' => $user->name,
                'family' => $user->family,
                'mobile' => $user->mobile,
                'token' => $user->remember_token,
            ], 200);
         else
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP',

            ], 401);


    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'userType' =>'required',
            'mobile' => 'required|numeric|digits:11',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'nationalCode' => 'required||digits:10|numeric',
            'password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/\d/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }


        $user = User::where('mobile', $request->mobile)->first();
        if($user){
            $user->update([
                'userType' => $request->userType,
                'mobile' => $request->mobile,
                'name' => $request->name,
                'family' => $request->family,
                'birthdate' => $request->birthdate,
                'nationalCode' => $request->nationalCode,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('API Token')->plainTextToken;
            $user -> remember_token = $token;
            $user->save();

            $wallet = Wallet::create([
                'user_id' => $user->id,
                'stock' => 0,
            ]);


            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $token,
                'name' => $user->name,
                'family' => $user->family,
                'mobile' => $user->mobile,
                'userType' => $user->userType
            ], 201);

        }
        else
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
    }





}
