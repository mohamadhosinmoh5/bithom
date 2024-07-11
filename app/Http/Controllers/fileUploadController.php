<?php

namespace App\Http\Controllers;

use App\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function UploadFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file.*' => 'required|max:4048',
            'type_file' => 'string',
            'model' => 'string',
            'mobile' => 'required|numeric|digits:11',
            'project_id' => ($request->project_id !== null) ? 'required|' : '',

        ]);
        if ($validator->fails())
            return response()->json([
                'error' => $validator->errors()
            ], 400);


        $user = User::where('mobile', $request->mobile)->first();
        if($user){
            if(empty($request->project_id))
                $type_id = $user->id;
            else
                $type_id = $request->project_id;

            $uploadedFiles = [];
            $type = [];

            if ($request->hasFile('file')) {
                $files = $request->file('file');

                foreach ($files as $file) {
                    $fileName = time(). '-'. $file->getClientOriginalName();
                    $type[] = $file->getMimeType();
                    $destinationPath = public_path('upload/files/');
                    $file->move($destinationPath, $fileName);
                    $uploadedFiles[] = 'upload/files/'.$fileName;
                }
            }

            $uploads = [];
            foreach($uploadedFiles as $uploadedFile )
            {
                $file = new File;
                $file->url = $uploadedFile;
                $file->model = $request->model;
                $file->type_id = $type_id;
                $file->type_file = $request->type_file;
                $file->mime_type = $type[array_search($uploadedFile, $uploadedFiles)];
                $file->save();
                $uploads[] = [
                    'id' => $file->id,
                    'url' => $uploadedFile,
                    'type' => $type
                ];
            }
            return response()->json([
                'status' => true,
                'message' => 'آپلود با موفقیت انجام شد.',
                'data' => $uploads
            ]);
        }else
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);

    }

}
