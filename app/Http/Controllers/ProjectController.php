<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Project;
use App\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function getProjects()
    {

        // $project = Project::with('mainImg')->get();
        $project = Project::with('mainImg')->orderBy('created_at', 'desc')->take(5)->get();

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
                ->with('file','unit','mainImg')
                ->first();


        // $remainingMeterage = $this->remainingMeterage($project);

        return response()->json([
            'status' => true,
            'project' => $project,

        ], 201);
    }


}
