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
        ],
        $messages = [
            'mobile.required' => 'فیلد موبایل الزامی است.',
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
            $this->generateRandomOTP($request->mobile);

            return response()->json([
                'status' => false,
            ], 200);
        }
    }

    public function updateOtp(Request $request)
    {
        $validator = validator::make($request->all(), [
            'mobile' => 'required',
        ]);

        if($validator->failed())
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors(),
            ], 400);

        $user = User::where('mobile', $request->mobile)->first();
        $user->otp = random_int(1000, 9999);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'رمز ارسال شد.',
        ], 201);

    }

    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',

            // 'password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/\d/',
        ],
        // $messages = [
        //     'password.required' => 'فیلد پسورد الزامی است.',
        //     'password.min:8' => 'پسورد باید حداقل 8 کاراکتر باشد.',
        //     'password.regex:/[a-z]/' => 'پسورد باید حاوی حرف کوچک باشد.',
        //     'password.regex:/[A-Z]/' => 'پسورد باید حاوی حرف بزرگ باشد.',
        //     'password.regex:/\d/' => 'پسورد باید حاوی رقم باشد.',
        // ]
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
            'mobile' => 'required',
            'otp' => 'required|min:4|max:4',
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

            'mobile' => 'required',
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


        $user = User::where('mobile', $request->mobile)->first();

        $user->update([
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

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $token,
            'name' => $user->name,
            'family' => $user->family,
        ], 201);


    }





}
