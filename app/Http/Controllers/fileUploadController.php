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
        ]);
        if ($validator->fails())
            return response()->json([
                'error' => $validator->errors()
            ], 400);

        $uploadedFiles = [];
        $type = [];

        if ($request->hasFile('file')) {
            $files = $request->file('file');


            foreach ($files as $file) {
                $fileName = time(). '-'. $file->getClientOriginalName();
                $type[] = $file->getMimeType();
                $destinationPath = public_path('upload/files/');
                $file->move($destinationPath, $fileName);
                $uploadedFiles[] = $fileName;
            }
        }

        $uploads = [];
        foreach($uploadedFiles as $uploadedFile )
        {
            $file = new File;
            $file->url = $uploadedFile;
            $file->type = $type[array_search($uploadedFile, $uploadedFiles)];
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
    }

}
