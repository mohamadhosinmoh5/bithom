<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\UserIdentityInformations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
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

    public function userUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'name' => ($request->name !== null) ? 'required' : '',
            'family' => ($request->family !== null) ? 'required' : '',
            'birthdate' => ($request->birthdate !== null) ? 'required' : '',
            'city' => ($request->city !== null) ? 'required' : '',
            'province' => ($request->province !== null) ? 'required' : '',
            'address' => ($request->address !== null) ? 'required' : '',
            'companyCode' => ($request->companyCode !== null) ?  : '',
            'nationalCard_file_id' => ($request->nationalCard_file_id !== null) ? 'required' : '',
            'video_file_id' => ($request->video_file_id !== null) ? 'required' : '',
            'profile_file_id' => ($request->profile_file_id !== null) ? 'required' : '',

        ]);
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        $user = User::where('mobile', $request->mobile)->first();
        if ($user->remember_token !== $request->header('Authorization'))
            return response()->json([
                'status' => false,
                'message' => 'توکن با موبایل همخوانی ندارد.'
            ], 201);
        else
        {
            $updates = [];

            if(empty($request->nationalCard_file_id) && (empty($request->video_file_id)) && (empty($request->profile_file_id)))
            {
                $updates['name'] = $request->name;
                $updates['family'] = $request->family;
                $updates['birthdate'] = $request->birthdate;
                $updates['city'] = $request->city;
                $updates['province'] = $request->province;
                $updates['address'] = $request->address;
                $updates['companyCode'] = $request->companyCode;

                $user->update($updates);
                return response()->json([
                    'status' => true,
                ], 201);
            }
            else
            {
                $uii = UserIdentityInformations::where('user_id', $user->id)->first();
                if($uii == null)
                    $uii = new UserIdentityInformations();

                if (!empty($request->nationalCard_file_id)){
                    $uii->nationalCard_file_id = $request->nationalCard_file_id;
                    $uii->nationalCard_file_status =  $uii::AWAITING_CONFIRMATION;
                }
                if (!empty($request->video_file_id)){
                    $uii->video_file_id = $request->video_file_id;
                    $uii->video_file_status = $uii::AWAITING_CONFIRMATION;
                }
                if (!empty($request->profile_file_id))
                {
                    $uii->profile_file_id = $request->profile_file_id;
                    $uii->profile_file_status = $uii::AWAITING_CONFIRMATION;
                }

                $uii->user_id = $user->id;
                $uii->save();

                return response()->json([
                    'status' => true,
                ], 201);
            }


        }


    }


























    // public function userInfo(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [

    //         'mobile' => 'required',
    //         'name' => 'required',
    //         'family' => 'required',
    //         'birthdate' => 'required',
    //         'city' => 'required',
    //         'province' => 'required',
    //         'address' => 'required',
    //         'companyCode',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }


    //     $user = User::where('mobile', $request->mobile)->first();

    //     $user->update([
    //         'name' => $request->name,
    //         'family' => $request->family,
    //         'birthdate' => $request->birthdate,
    //         'city' => $request->city,
    //         'province' => $request->province,
    //         'address' => $request->address,
    //         'companyCode' => $request->companyCode,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'اطلاعات شما ثبت شد.',
    //     ], 201);

    // }


    // public function uploadNationalCardImg(Request $request)
    // {
    //     $token = $request->header('Authorization');
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required',
    //         'mobile' => 'required',
    //     ]);

    //     if ($validator->fails())
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validator->errors(),
    //         ], 400);

    //     if (!Auth::attempt(['mobile' , 'remember_token' => $token]));
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'شماره تلفن با توکن همخوانی ندارد.',
    //         ], 401);


    //     $user = User::where('mobile', $request->mobile)->first();

    //     $user->update([
    //         'nationalCard_img' => $request->id,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'آپلود انجام شد.',
    //     ], 201);



    // }
}
