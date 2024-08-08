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

    public function deleteProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric',
            'project_id' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);

        $user = User::where('mobile', $request->mobile)->first();
        if($user){

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
        return response()->json([
            'status' => false,
            'message' => 'کاربر وجود ندارد.',
        ], 400);
    }

}
