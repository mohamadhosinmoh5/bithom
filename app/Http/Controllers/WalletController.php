<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transaction;
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
        return response()->json([
            'status' => true,
            'wallet' => $wallet
        ], 201);
    }

    public function getTransactions(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => ($request->mobile !== null) ? 'required|numeric|digits:11' : '',
            'transaction_id' => ($request->transaction_id !== null) ? 'required|' : '',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        if(!empty($request->mobile)){

            $user = User::where('mobile', $request->mobile)->first();
            $transaction = $user->wallet->transaction;

        }

        if(!empty($request->transaction_id))
            $transaction = Transaction::findOrFail($request->transaction_id);

        return response()->json([
            'status' => true,
            'transaction' => $transaction
        ], 201);


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
