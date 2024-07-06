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

        $product = Product::create([
            'user_id' => $project->user_id,
            'project_id' => $request->id,
            'wallet_id' =>


        ]);

        $brickPrice = $this->brickPrice($project);


        return response()->json([
            'status' => true,
            'investment_status' => $project->investment_status,
            'title' => $project->title,
            'baseTitle' => $project->baseTitle,
            'brickPrice' => $brickPrice,
        ], 201);
    }

    public function brickPrice($data)
    {
        $brickPrice = $data->price / 10000;
        $data->product->brick_price = $brickPrice;
        $data->save();
        return $brickPrice;
    }

    public function brickNumber($data ,$amount)
    {
        $brickNumber = $amount / $data->brick_price;
        $data->brick_number = $brickNumber;
        $data->save();
        return $brickNumber;

    }

    public function investmentMeterage($data)
    {
        $investmentMeterage = $data->brick_number / 10000;
        $data->investment_meterage = $investmentMeterage;
        $data->save();
        return $investmentMeterage;

    }


}
