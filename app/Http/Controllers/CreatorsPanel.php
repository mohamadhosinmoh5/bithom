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
        $totalMeterage = $user->project->sum('meterage');

        return response()->json([
            'status' => true,
            'project' => $project,
            'totalMeterage' => $totalMeterage

        ], 201);
    }
}
