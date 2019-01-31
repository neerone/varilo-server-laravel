<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\ProjectFile;
use Auth;
use Storage;


class ApiController extends Controller
{
    public function projectGet($project_id)
    {

        $project = Project::where("id", $project_id)->first();
        if (!isset($project)) return response()->json([]);

        $json_response = json_decode($project->project_state);

        $assets = $project->assets();
        if ($assets->count()>0) {
                
            $outputAssets = [];
            foreach ($assets as $index => $asset) {
                $outputAssets[$asset->name] = [
                    "id" => $asset->id,
                    "fullpath" => $asset->fullpath,
                    "localpath" => $asset->localpath,
                    "type" => $asset->type,
                    "name" => $asset->name,
                    "loaded" => true
                ];
            }
            $json_response->assets = $outputAssets;
        }
        $files = $project->files();
        if ($files->count()>0) {
                
            $outputFiles = [];
            foreach ($files as $index => $file) {
                $outputFiles[$file->name] = [
                    "id" => $file->id,
                    "fullpath" => $file->fullpath,
                    "localpath" => $file->localpath,
                    "type" => $file->type,
                    "name" => $file->name,
                    "loaded" => true
                ];
            }
            $json_response->files = $outputFiles;
        }


        return response()->json($json_response);
    }
    public function projectSave(Request $request)
    {

    	$state = $request->input('state');
    	$project_id = $request->input('project_id');




        $project = Project::where("id", $project_id)->first();
        if (!$project) return null;
        $project->project_state = json_encode($state);
        $project->need_bake = true;
        $project->save();

        return 1;
    }


    public function filesUpload(Request $request)
    {
        $files = $request->file('files');
        $type = $request['type'];
        $project_id = $request->input('project_id');
        $userId = Auth::user()->id;


        $output = [];

        foreach ($files as $index => $file) {
            # code...
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('public/projects/'.$userId.'/'.$project_id.'/assets', $filename);
            $localpath = 'assets/' . $filename;
            $src = Storage::disk('local')->url($path);
            
            $pf = new ProjectFile();
            $pf->project_id = $project_id;
            $pf->name = $filename;
            $pf->fullpath = $src;
            $pf->localpath = $localpath;
            $pf->type = $type;
            $pf->version = 1;
            $pf->save();

            $output[$filename] = [
                "id" => $pf->id,
                "fullpath" => $src,
                "localpath" => $localpath,
                "type" => $type,
                "name" => $filename,
                "loaded" => true
            ];

        }


        return json_encode($output);
    }

    public function loadFile(Request $request) {
        $fullpath = $request->input('full_path');
        $project_id = $request->input('project_id');
        $userId = Auth::user()->id;
        $output = [];

        // Сделать нормальный путь, storage может совпадать с именем пользовательского файла
        $realStoragePath = str_replace("/storage", "/public", $fullpath);

        $fileObject = ProjectFile::where("fullpath", $fullpath)->where("project_id", $project_id)->first();

        if (!$fileObject) abort(500, "File not found");

        $fileData = Storage::disk('local')->get($realStoragePath);
        //dd($fileData);

        $outputFiles = [];
        $outputFiles[$fileObject->name] = $fileObject;

        $outputData = [];
        $outputData[$fileObject->name] = $fileData;


        $output["files"] = $outputFiles;
        $output["data"] = $outputData;



        return json_encode($output);
    }





    public function changeFile(Request $request) {
        $fullpath = $request->input('full_path');
        $project_id = $request->input('project_id');
        $newContent = $request->input('new_content');
        $userId = Auth::user()->id;
        $output = [];

        // Сделать нормальный путь, storage может совпадать с именем пользовательского файла
        $realStoragePath = str_replace("/storage", "/public", $fullpath);

        $fileObject = ProjectFile::where("fullpath", $fullpath)->where("project_id", $project_id)->first();

        if (!$fileObject) abort(500, "File not found");
        $fileObject->version = $fileObject->version + 1;
        $fileObject->save();



        $fileData = Storage::disk('local')->put($realStoragePath, $newContent);
        




        $outputFiles = [];
        $outputFiles[$fileObject->name] = $fileObject;

        $output["files"] = $outputFiles;


        return json_encode($output);
    }


}
