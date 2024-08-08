<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CreatorsPanel extends Controller
{
    public function createProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'address'=> 'required|string',
            'meterage' => 'required|numeric',
            'attechment_file_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::where('mobile', $request->mobile)->first();
        if($user){
            $project = Project::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'price' => $request->price,
                'address' => $request->address,
                'meterage' => $request->meterage,
                'attechment_file_id' => $request->attechment_file_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'پروژه ثبت شد.',
                'project' => $project
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => 'کاربر وجود ندارد.',
        ], 400);


    }

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

    public function getProjects(Request $request)
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
        $projects = $user->project;

        if(count($projects) == 0)
            return response()->json([
                'status' => false,
                'message' => 'پروژه ای وجود ندارد.',
            ], 400);

        $projectData = [];

        foreach ($projects as $project) {
            $totalMeterage = $project->meterage; // مقدار عرضه شده
            $remaining_meterage = $project->remaining_meterage; // مقدار باقی‌مانده
            $quantitySold = $totalMeterage - $remaining_meterage; // مقدار فروخته شده
            $projectData[] = [
                'title' => $project->title,
                'project_id' => $project->id,
                'totalMeterage' => $totalMeterage,
                'quantitySold' => $quantitySold,
                'currentPrice' => $project->currentPrice,
            ];
        }

            return response()->json([
                'status' => true,
                'projects' => $projectData,
            ], 201);
    }

    public function deleteProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        $project = Project::findOrFail($request->project_id);

        if ($project) {
            $project->delete();
        }else{
            return response()->json([
                'status' => false,
                'message' => 'پروژه وجود ندارد.',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'پروژه حذف شد.',
        ],201);
    }

    public function getProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $project = Project::where('project_id', $request->id)
                ->with('file','unit','mainImg')
                ->first();

        return response()->json([
            'status' => true,
            'project' => $project,

        ], 201);
    }



}
