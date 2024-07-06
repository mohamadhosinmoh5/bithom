<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetManagementController extends Controller
{
    public function myAssets(Request $request)
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
        $wallet = $user->wallet;
        if(empty($wallet))
            return response()->json([
                'status' => false,
                'message' => 'کیف پولی برای این کاربر وجود ندارد.',
            ], 400);

        return response()->json([
            'status' => true,
            'wallet' => $wallet
        ], 201);


    }

    public function trades()
    {

    }

    public function orders()
    {

    }
}
