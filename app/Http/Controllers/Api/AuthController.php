<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $exists = User::where('mobile', $request->mobile)->exists();

        return response()->json([
            'status' => true,
            'exists' => $exists,
        ], 200);
    }


    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',
        ]);

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
        ], 200);
    }

    function generateRandomOTP(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::where('mobile', $request->mobile)->first();

        $otp =  random_int(1000, 9999);
        $user -> otp = $otp;
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'saveCode',
        ], 201);
    }

    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'otp' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if ($user && $user->otp == $request->otp) {

            return response()->json([
                'status' => true,
                'message' => 'Correct OTP',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP',
            ], 401);
        }

    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'family' => 'required',
            'birthdate' => 'required',
            'nationalCode' => 'required|min:10',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'birthdate' => $request->birthdate,
            'nationalCode' => $request->nationalCode,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;
        $user -> remember_token = $token;
        $user->save();


        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $token,
        ], 201);


    }

}
