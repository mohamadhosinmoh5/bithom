<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\UserIdentityInformations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{

    public function chengePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric',
            'password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/\d/',
            'newPass' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/\d/',
            'confirm' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/\d/',
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
                        'message' => 'گذرواژه تغییر یافت.',
                    ]
                ,201);
        } else
            return response()->json([
                'status' => false,
                'message' => 'گذرواژه و تکرار یکسان نیست.',
            ]);
    }

    public function getUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
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
            'mobile' => 'required|numeric|digits:11',
            'name' => ($request->name !== null) ? 'required|string|max:255' : '',
            'family' => ($request->family !== null) ? 'required|string|max:255' : '',
            'birthdate' => ($request->birthdate !== null) ? 'required|date' : '',
            'city' => ($request->city !== null) ? 'required|string|max:255' : '',
            'province' => ($request->province !== null) ? 'required|string|max:255' : '',
            'address' => ($request->address !== null) ? 'required|string' : '',
            'companyCode' => ($request->companyCode !== null) ?  : '',
            'nationalCard_file_id' => ($request->nationalCard_file_id !== null) ? 'required|numeric' : '',
            'video_file_id' => ($request->video_file_id !== null) ? 'required|numeric' : '',
            'profile_file_id' => ($request->profile_file_id !== null) ? 'required|numeric' : '',

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
                    $uii->user_id = $user->id;
                    $uii->save();
                    return response()->json([
                        'status' => true,
                        'nationalCard_file_status' => $uii->nationalCard_file_status
                    ], 201);
                }
                if (!empty($request->video_file_id)){
                    $uii->video_file_id = $request->video_file_id;
                    $uii->video_file_status = $uii::AWAITING_CONFIRMATION;
                    $uii->user_id = $user->id;
                    $uii->save();
                    return response()->json([
                        'status' => true,
                        'video_file_status' => $uii->video_file_status
                    ], 201);
                }
                if (!empty($request->profile_file_id))
                {
                    $uii->profile_file_id = $request->profile_file_id;
                    $uii->profile_file_status = $uii::AWAITING_CONFIRMATION;
                    $uii->user_id = $user->id;
                    $uii->save();
                    return response()->json([
                        'status' => true,
                        'profile_file_status' => $uii->profile_file_status
                    ], 201);
                }

            }
        }
    }

    public function getUserFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $user = User::where('mobile', $request->mobile)->first();
        $identity = $user->identityInformation;
        if($identity->video_file_id == null && $identity->nationalCard_file_id == null)
            return response()->json([
                'status' => false,
                'message' => 'احراز هویت نشده اید'
            ], 201);

        if($identity->video_file_id == null){
            $id = $identity->nationalCard_file_id;
            $url = File::findOrFail($id)->url;
            return response()->json([
                'nationalCard_file_status' => $identity->nationalCard_file_status,
                'nationalCard_file_url' => $url
            ], 201);
        }

        if($identity->nationalCard_file_id == null){
            $id = $identity->video_file_id;
            $url = File::findOrFail($id)->url;
            return response()->json([
                'video_file_status' => $identity->video_file_status,
                'video_file_url' => $url
            ], 201);
        }
        else
            return response()->json([
                'status' => true,
            ], 201);

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
