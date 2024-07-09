<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetManagementController extends Controller
{

    public function myAssets(Request $request)
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

        $buyController = New BuyController;


        $user = User::where('mobile', $request->mobile)->first();

        $transactions = $user->wallet->transaction;

        $totalProfit = 0;
        $dayValues = 0;
        $totalCost = 0;
        $investmentAmount = 0;
        $products = [];

        foreach($transactions as $transaction){
            $product =[];

            if($transaction->project_id != null){
                // میزان سرمایه گذاری = مجموع مقادیر داخل تراکنش های که مربوط به یک پروژه اند.
                $investmentAmount += $transaction->amount;

                // سودکل
                $project = Project::findOrFail($transaction->project_id);
                $dayValues += $project->currentPrice * $buyController->investmentMeterage($project , $transaction->amount);


                $project = Project::findOrFail($transaction->project_id);
                $product['title'] = $project->title;
                $product['baseTitle'] = $project->baseTitle;
                $product['currentPrice'] = $project->currentPrice;
                $product['investmentPrice'] = $transaction->amount;

                // متراژ خرید شده
                //میزان سرمایه گذاری/قیمت درحال هر متر
                $investmentMeterage = $buyController->investmentMeterage($project , $transaction->amount);
                $product['investmentMeterage'] = $investmentMeterage;

                // متراژ خریده شده *  قیمت فعلی = ارزش روز کل سرمایه
                $dayValue = $project->currentPrice * $investmentMeterage;
                $product['dayValue'] = $dayValue;

                array_push($products, $product);
            }
        }
        $totalProfit = $dayValues - $investmentAmount;







        return response()->json([
            'status' => true,
            'stock' => $user->wallet->stock,
            'investmentAmount' => $investmentAmount,
            'products' => $products,
            'totalProfit' => $totalProfit
        ], 201);

    }




    public function trades()
    {
    }

    public function orders()
    {
    }
}
