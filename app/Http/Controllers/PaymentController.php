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
    public function Payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
            'amount' =>'required|numeric',
            'wallet_id' => 'required|numeric'
        ]);
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        $data = [
            'mobile' => $request->mobile,
            'callbackUrl' => "http://127.0.0.1:8000/api/getWallet",
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

        if($responseBody["result"] == 100)
        {

            $transaction = Transaction::create([
                'wallet_id' => $request->wallet_id,
                'transaction_type' => Transaction::DIRECT,
                'status' => Transaction::UNSUCCESSFUL,
                'price' => $request->amount,
                'trackId' => $responseBody["trackId"]
            ]);

            $trackId = $responseBody["trackId"];
            $baseUrl = 'https://gateway.zibal.ir/start/';
            $paymentPageUrl = $baseUrl . $trackId;
            return response()->json([
                'status' => true,
                'paymentPageUrl' => $paymentPageUrl
            ],201 );

        }else
            return response()->json([
                'status' => false,
            ],201 );

    }




    // public function startPaying()
    // {

    //     $trackId = '3662736270';
    //     $baseUrl = 'https://gateway.zibal.ir/start/';
    //     $paymentPageUrl = $baseUrl . $trackId;

    //     return redirect($paymentPageUrl);

    // }























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
