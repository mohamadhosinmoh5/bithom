<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CreatorsPanel extends Controller
{
    public function creator(Request $request)
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
        $project = $user->project;
        //مقدار عرضه شده
        $totalMeterage = $user->project->sum('meterage');

        //مقدار فروخته شده
        $remaining_meterages = $user->project->sum('remaining_meterage');
        $quantitySold = $totalMeterage - $remaining_meterages;

        //تعداد خریداران
        $buyerCount = $user->project->sum(function ($project) {
            return $project->product->count();
        });

        return response()->json([
            'status' => true,
            'project' => $project,
            'totalMeterage' => $totalMeterage,
            'quantitySold' => $quantitySold,
            'buyerCount' => $buyerCount

        ], 201);
    }
}
