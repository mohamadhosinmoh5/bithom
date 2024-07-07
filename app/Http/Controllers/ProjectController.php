<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function getProjects()
    {

        $project = Project::get();
        if(count($project) == 0)
            return response()->json([
                'status' => false,
                'message' => 'پروژه ای برای وجود ندارد.',
            ], 400);

        return response()->json([
            'status' => true,
            'project' => $project,
        ], 201);
    }

    public function getProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $project = Project::where('id', $request->id)
                ->with('projectFile')
                ->with('unit')
                ->first();

        // $remainingMeterage = $this->remainingMeterage($project);

        return response()->json([
            'status' => true,
            'project' => $project,
        ], 201);
    }


    // public function remainingMeterage($data)
    // {
    //     //متراژباقی مانده = متراژ خریدشده - متراژمفید

    //     $data->meterage - ;
    // }

    // public function realizedProfit($data)
    // {
    //     //سود محقق شده
    //     $data->price - ;


    // }
}
