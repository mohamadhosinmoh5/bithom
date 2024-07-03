<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getWallet(Request $request)
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
}
