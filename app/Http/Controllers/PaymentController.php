<?php

namespace App\Http\Controllers;

use App\Classes\BankPortal\ZibalPortal;
use App\Models\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public $usePaymentController = false;
    public $payumentProductId = 0;

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


            $requestData = [
                'mobile' => $request->mobile,
                'amount' => $request->amount,
            ];

            if($this->usePaymentController){
               // $requestData['callbackUrl'] =  "http://127.0.0.1:8000/api/callbackUrl/?payumentProductId=$this->payumentProductId";

                $requestData['callbackUrl'] =  "http://127.0.0.1:8000/api/callbackUrl/?paymentProductId=$this->payumentProductId";
            }else{
                $requestData['callbackUrl'] =  "http://127.0.0.1:8000/api/callbackUrl";}

        if(true){
            $Zibal = New ZibalPortal;
            $responseBody =
                $Zibal->Request($requestData);
        }
        if($responseBody){
            $transaction = Transaction::create([
                'wallet_id' => $request->wallet_id,
                'transaction_type' => Transaction::DIRECT,
                'status' => Transaction::UNSUCCESSFUL,
                'amount' => $request->amount,
                'trackId' => $responseBody["trackId"]
            ]);

            if($this->usePaymentController){
                return[
                    'status' => true,
                    'paymentPageUrl' => $responseBody['paymentPageUrl'],
                    'trackId' => $responseBody["trackId"]

                ];

            }else{
                return response()->json([
                    'status' => true,
                    'paymentPageUrl' => $responseBody['paymentPageUrl'],
                    'trackId' => $responseBody["trackId"]

                ],201 );

            }
        }
        dd("خطای درگاه");
    }

    public function callbackUrl(Request $request)
    {
        $transaction = Transaction::where('trackId', $request->trackId)->first();
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        if($request->success){
            $Verify = New ZibalPortal;
            $responseBody =
                $Verify->Verify([
                    'trackId' => $request->trackId,
                ]);

        //     if($responseBody["result"] == 100)
        //     {
        //         $transaction->status = Transaction::SUCCESSFUL;
        //         $transaction->reference_code = $responseBody["refNumber"];
        //         $transaction->save();

        //         $this->increment($transaction);

        //         return response()->json([
        //             'status' => true,
        //             'amount' => $responseBody["amount"],
        //             'message' => $responseBody["message"],
        //             'result' => $responseBody["result"],
        //             'refNumber' => $responseBody["refNumber"],
        //             'txStatus' => $responseBody["status"]
        //         ],201 );
        //     }
        //     else
        //         return response()->json([
        //             'status' => false,
        //             'txStatus' => $responseBody["status"],
        //             'message' => $responseBody["message"],
        //             'result' => $responseBody["result"],
        //         ],400 )
        //     ;
        // }else{
        //     return response()->json([
        //         'status' => false,
        //         'message' => "عملیات ناموفق",
        //     ],400 );
        // }


        if($responseBody["result"] == 100)
        {
            $transaction->status = Transaction::SUCCESSFUL;
            $transaction->reference_code = $responseBody["refNumber"];
            $transaction->save();

            $this->increment($transaction);
            if(array_key_exists('payumentProductId',$_GET)){
                $payumentProductId = $_GET['payumentProductId'];

                return redirect("localhost:3000/wallet/detail-payment/?transaction_id=$transaction->id,product_id=$payumentProductId");
            }
            else
                return redirect("localhost:3000/wallet/detail-payment/?transaction_id=$transaction->id");
        }
    }else{
        return response()->json([
            'status' => false,
            'message' => "عملیات ناموفق",
        ],400 );
    }
    }

    public function increment($data)
    {
        $stock = ($data->wallet->stock) + ($data->amount);
        $data->wallet->stock = $stock;
        $data->wallet->save();

        $data->operation_type = Transaction::INCREMENT;
        $data->save();
    }




















}
