<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// require 'vendor/autoload.php';
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public function payWithZibal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
            'amount' =>'required|numeric',
            'callbackUrl' => 'required'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        $data = [
            'mobile' => $request->mobile,
            'callbackUrl' => $request->callbackUrl,
            'amount' => $request->amount,
            'merchant' => "zibal",
        ];

        $client = new Client([
            'base_uri' => 'https://gateway.zibal.ir/v1/request',
        ]);

        $response = $client->post('/request', [
            'json' => $data,
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        return response()->json($responseBody);

    }
























    // public function zibal(Request $request)
    // {
    //     //ثبت تراکنش
    //     $validator = Validator::make($request->all(), [
    //         'mobile' => 'required|numeric|digits:11',
    //         'wallet_id' => 'required|numeric',
    //         'amount' => 'required|numeric'
    //     ]);

    //     if ($validator->fails())
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     if(User::where('mobile', $request->mobile)->first())
    //     {
    //         $transaction = Transaction::create([
    //             'wallet_id' => $request->wallet_id,
    //             'transaction_type' =>
    //             'price' =>  $request->amount,
    //             'status' =>
    //             'reference_code' =>
    //         ]);
    //     }
    // }
}
