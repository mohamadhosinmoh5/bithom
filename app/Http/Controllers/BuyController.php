<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Product;
use App\Project;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\PaymentController;
use App\Wallet;

class BuyController extends Controller
{
    public function getBuyIformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'mobile' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        $project = Project::where('id', $request->project_id)
            ->first();
        $currentPrice = $project->currentPrice;


        $brickPrice = $this->brickPrice($project);
        $brickNumber = $this->brickNumber($project , $request->amount);
        $investmentMeterage = $this->investmentMeterage($project , $request->amount);
        $investmentPrice = $this->investmentPrice($project , $request->amount);
        $wage = $this->wage($project , $request->amount);
        $tax = $this->tax($project , $request->amount);
        $payable = $this->payable($project , $request->amount);



        $user = User::where('mobile', $request->mobile)->first();
        $user->investmentPrice = $investmentPrice;
        $user->save();

        return response()->json([
            'status' => true,
            'investment_status' => $project->investment_status,
            'title' => $project->title,
            'baseTitle' => $project->baseTitle,
            'brickPrice' => $brickPrice,
            'brickNumber' => $brickNumber,
            'investmentMeterage' => $investmentMeterage,
            'investmentPrice' => $investmentPrice,
            'wage' => $wage,
            'tax' => $tax,
            'payable' => $payable,
            'stock' => $user->wallet->stock,
            


        ], 201);
    }

    public function buy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'mobile' => 'required|numeric|digits:11',
            //payable
            'amount' =>'required|numeric',
            'wallet_id' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $wallet = Wallet::findOrFail($request->wallet_id);
        $diff = $wallet->stock - $request->amount;// 100 - 200

        if($diff < 0){
            $request2 = new \Illuminate\Http\Request;
            $request2->replace([
                'mobile' => $request->mobile,
                'wallet_id' => $request->wallet_id,
                'amount' => $diff * -1,
            ]);

            $payment = New PaymentController;
            //$response = $payment->usePaymentController = true;
            //$response = $payment->payumentProductId = $request->product_id;
            $payment->payumentProductId = $request->product_id;
            $payment->usePaymentController = true;
            $response = $payment->Payment($request2);

            if($response){
                $transaction = Transaction::create([
                    'wallet_id' => $request->wallet_id,
                    'transaction_type' => Transaction::DIRECT,
                    'status' => Transaction::UNSUCCESSFUL,
                    'amount' => $request->amount,
                    'trackId' => $response["trackId"],
                    'project_id' => $request->product_id,
                ]);
                $this->decrement($transaction , $wallet->stock);

                return response()->json([
                    'status' => true,
                    'paymentPageUrl' => $response['paymentPageUrl'],
                ],201 );

            }else{
                dd("نشد");
            }
        }
        else{
            $transaction = Transaction::create([
                'wallet_id' => $request->wallet_id,
                'transaction_type' => Transaction::WALLET,
                'status' => Transaction::UNSUCCESSFUL,
                'amount' => $request->amount,
                'operation_type' => Transaction::DECREMENT,
                'project_id' => $request->product_id,
            ]);
            $this->decrement($transaction , $request->amount);

        }
        return response()->json([
            'status' => true,
        ],201 );
    }



    public function decrement($data , $amount)
    {

        $stock = ($data->wallet->stock) - $amount;
        $data->wallet->stock = $stock;
        $data->wallet->save();

        $data->operation_type = Transaction::DECREMENT;
        $data->save();

    }

    public function brickPrice($data)
    {
        $brickPrice = $data->price / 10000;
        return $brickPrice;
    }

    public function brickNumber($data ,$amount)
    {
        $brickNumber = $amount / $this->brickPrice($data);
        return floor($brickNumber);
    }

    public function investmentMeterage($data ,$amount)
    {
        $investmentMeterage = $this->brickNumber($data ,$amount) / 10000;
        return $investmentMeterage;
    }

    public function investmentPrice($data ,$amount)
    {
        $investmentPrice = $this->brickNumber($data ,$amount) * $this->brickPrice($data);
        return $investmentPrice;
    }

    public function wage($data ,$amount)
    {
        $wage = $this->investmentPrice($data ,$amount) * ($data->projectConfig->fee_percentage) / 100;
        return ceil($wage);
    }

    public function tax($data ,$amount)
    {
        $tax = $this->wage($data ,$amount) * ($data->projectConfig->tax_percentage) / 100;
        return ceil($tax);

    }
    public function payable($data ,$amount)
    {
        $payable = $this->tax($data ,$amount) + $this->wage($data ,$amount) + $this->investmentPrice($data ,$amount);
        return $payable;
    }



}
