<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function zibal(Request $request)
    {
        //ثبت تراکنش
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
            'wallet_id' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        if(User::where('mobile', $request->mobile)->first())
        {
            $transaction = Transaction::create([
                'wallet_id' => $request->wallet_id,
                'transaction_type' =>
                'price' =>  $request->amount,
                'status' =>
                'reference_code' => 
            ]);
        }


        // return response()->json([
        //     'merchant' => "zibal",
        //     'amount'=> $request->amount,
        //     'callbackUrl'=> "http://yourapiurl.com/callback.php",
        //     'description'=> "Hello World!",
        //     'orderId'=> "ZBL-7799",
        //     'mobile'=> $request->mobile,
        // ], 201);
    }
}
