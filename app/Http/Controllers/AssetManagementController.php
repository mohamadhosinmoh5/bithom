<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $user = User::where('mobile', $request->mobile)->first();


        // قیمت فعلی هر خشت * تعداد خشت = ارزش روز کل سرمایه
         $dayValue = $this->brickNumber($project , $request->amount) * ($currentPrice / 10000);
        // سودکل
        $totalProfit = $dayValue - $user->investmentPrice;


        return response()->json([
            'status' => true,
            'stock' => $user->wallet->stock,
            'investmentPrice' => $user->investmentPrice,
        ], 201);

    }




    public function trades()
    {
    }

    public function orders()
    {
    }
}
