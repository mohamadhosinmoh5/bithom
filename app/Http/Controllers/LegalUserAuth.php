<?php

namespace App\Http\Controllers;

use App\Company;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LegalUserAuth extends Controller
{
    public function createCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'userType' =>'required',
            'mobile' => 'required|numeric|digits:11|exists:users,mobile',

            'comany_name' => 'required|string|max:255',
            'company_type' => 'required|string|max:255',
            'registration_num' => 'required|numeric',
            'registration_date' => 'required|date',
            'registration_city'=> 'required|string',
            'logo' => 'required|string|max:255',
            'company_email'=> 'required|string',
            'phone'=> 'required|numeric|digits:11',
            'postal_code'=> 'required|numeric|digits:10',
            'local_city'=> 'required|string',
            'national_code' => 'required||digits:10|numeric',
            'company_address' => 'required|string|max:255',
            'userType' =>'required',

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
            ]);
            $user->save();

            $company = Company::create([
                'user_id' => $user->id,

                'comany_name' => $request->comany_name,
                'company_type' => $request->company_type,
                'registration_num' => $request->registration_num,
                'registration_date' => $request->registration_date,
                'registration_city'=> $request->registration_city,
                'logo' => $request->logo,
                'company_email'=> $request->company_email,
                'phone'=> $request->phone,
                'postal_code'=> $request->postal_code,
                'local_city'=> $request->local_city,
                'company_address' => $request->company_address,
            ]);

            return response()->json([
                'status' => true,
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
