<?php

namespace App\Http\Controllers;

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

        return response()->json([
            'status' => true,
            'investment_status' => $project->investment_status,
            'title' => $project->title,
            'baseTitle' => $project->baseTitle,
            'brickPrice' => $brickPrice,
            'brickNumber' => $brickNumber,
            'investmentMeterage' => $investmentMeterage,
            'investmentPrice' => $investmentPrice,


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


}
