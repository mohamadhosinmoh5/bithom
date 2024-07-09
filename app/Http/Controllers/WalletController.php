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
                'wallet' => '0'

            ], 200);

        return response()->json([
            'status' => true,
            'wallet' => $wallet
        ], 201);
    }

    public function getTransactions(Request $request)
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
        if(!empty($user->wallet)){
            $transaction = $user->wallet->transaction;
            if(empty($transaction))
                return response()->json([
                    'status' => false,
                ], 400);

            return response()->json([
                'status' => true,
                'transaction' => $transaction
            ], 201);
        }else
            return response()->json([
                'status' => false,
            ], 400);

    }




    // public function getWalletPage(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'mobile' => 'required|numeric|digits:11',
    //     ]);

    //     if ($validator->fails())
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validator->errors(),
    //         ], 400);


    //     $user = User::where('mobile', $request->mobile)->first();
    //     $this->getWallet($user);
    //     $this->getTransactions($user);

    // }


}
