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
            'supply_status' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'maker'=> 'required|string|max:255',
            'start_time' => 'required|string|max:255',
            'end_time' => 'required|string|max:255',
            'price' => 'required|numeric',
            'address'=> 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'area' => 'required|string|max:255',
            'projectType_id' => 'required|numeric',
            'meterage' => 'required|numeric',
            'floor' => 'required|numeric',
            'base_price' => 'required|numeric',
            'project_info' => ($request->project_info !== null) ? 'required|string' : '',
            'investment_status' => 'required|string|max:255',
            'remaining_meterage' => 'required|numeric',
            'start_price_investment' => 'required|numeric',
            'supply_status_code' => 'required|numeric',
            'baseTitle' => 'required|string|max:255',
            'currentPrice'=> 'required|numeric',
            'main_img_id'=> 'required|numeric',
            'store'  => ($request->store !== null) ? 'required|numeric' : '',
            'parking' => ($request->parking !== null) ? 'required|numeric' : '',
            'room'  => ($request->room !== null) ? 'required|numeric' : '',
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
                'supply_status' => $request->supply_status,
                'title' => $request->title,
                'city' => $request->city,
                'maker' => $request->maker,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'price' => $request->price,
                'address' => $request->address,
                'lat' => $request->lat,
                'long' => $request->long,
                'area' => $request->area,
                'projectType_id' => $request->projectType_id,
                'meterage' => $request->meterage,
                'floor' => $request->floor,
                'base_price' => $request->base_price,
                'project_info' => $request->project_info,
                'investment_status' => $request->investment_status,
                'remaining_meterage' => $request->remaining_meterage,
                'start_price_investment' => $request->start_price_investment,
                'supply_status_code' => $request->supply_status_code,
                'baseTitle' => $request->baseTitle,
                'currentPrice' => $request->currentPrice,
                'main_img_id' => $request->main_img_id,
            ]);

            $unit = Unit::create([
                'project_id' => $project->id,
                'title' => $project->title,
                'price' => $project->price,
                'projectType_id' => $project->projectType_id,
                'meterage' => $project->meterage,
                'remaining_meterage' => $project->remaining_meterage,
                'store' => $request->store,
                'parking' => $request->parking,
                'room' => $request->room,
            ]);
            $project = Project::findOrFail($project->id)->unit;
            $project  = $unit->id->save();

            return response()->json([
                'status' => true,
                'message' => 'پروژه ثبت شد.',
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
