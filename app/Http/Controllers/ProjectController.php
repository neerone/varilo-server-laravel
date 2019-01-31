<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Project;


class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add()
    {
    	$user_id = Auth::user()->id;

    	$project = new Project();
    	$project->name = "Новый проект";
    	$project->user_id = $user_id;
    	$project->project_state = "{}";
    	$project->save();
		return redirect('projects/'.$project->id);
    }


    public function delete($project_id)
    {

    	Project::where("id", $project_id)->delete();
		return redirect('home');
    }

    public function open($project_id)
    {
        return view('editor', ["project_id" => $project_id]);
    }


    public function preview($project_id) {
        $userId = Auth::user()->id;
        $project = Project::where("id", $project_id)->first();
        $project->bake();
        return redirect('storage/projects/'.$userId.'/'.$project_id.'/index.html?v='. uniqid());
    }




}
