<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Product;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuyController extends Controller
{
    public function buy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'amount' => 'required|numeric',
            'mobile' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        $project = Project::where('id', $request->id)
            ->first();

        $brickPrice = $this->brickPrice($project);
        $brickNumber = $this->brickNumber($project , $request->amount);
        $investmentMeterage = $this->investmentMeterage($project , $request->amount);
        $investmentPrice = $this->investmentPrice($project , $request->amount);
        $wage = $this->wage($project , $request->amount);
        $tax = $this->tax($project , $request->amount);
        $payable = $this->payable($project , $request->amount);

        $user = User::where('mobile', $request->mobile)->first();
        $user->investmentPrice = $investmentPrice;
        $user->totalProfit = $this->totalProfit($project , $request->amount);
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
            'stock' => $user->wallet->stock

        ], 201);
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

    public function totalProfit($data ,$amount)
    {
        $totalProfit = (($data->currentPrice - $this->investmentPrice($data ,$amount)) * 100 ) / $this->investmentPrice($data ,$amount);
        return $totalProfit;
    }


}
