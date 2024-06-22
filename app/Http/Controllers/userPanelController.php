<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class userPanelController extends Controller
{

    public function chengePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',
            'newPass' => 'required',
            'confirm' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        if (!Auth::attempt($request->only(['mobile', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials',
            ], 401);
        }

        if ($request->newPass ==  $request->confirm) {

            User::where('mobile', $request->mobile)
                ->first()
                ->update(
                    [
                        'password' => Hash::make($request->newPass),
                    ]
                );

            return response()
                ->json(
                    [
                        'status' => true,
                        'message' => 'رمزعبور تغییر یافت.',
                    ]
                ,201);
        } else
            return response()->json([
                'status' => false,
                'message' => 'رمز عبور و تکرار یکسان نیست.',
            ]);
    }

    public function getUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $user = User::where('mobile', $request->mobile)->first();

        return response()->json([
            'status' => true,
            'name' => $user->name,
            'family' => $user->family,
            'birthdate' => $user->birthdate,
            'city' => $user->city,
            'province' => $user->province,
            'address' => $user->address,


        ], 201);
    }

    public function userInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => 'required',
            'name' => 'required',
            'family' => 'required',
            'birthdate' => 'required',
            'city' => 'required',
            'province' => 'required',
            'address' => 'required',
            'companyCode',
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
            'name' => $request->name,
            'family' => $request->family,
            'birthdate' => $request->birthdate,
            'city' => $request->city,
            'province' => $request->province,
            'address' => $request->address,
            'companyCode' => $request->companyCode,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'اطلاعات شما ثبت شد.',
        ], 201);

    }


}
