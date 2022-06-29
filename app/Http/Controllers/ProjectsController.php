<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\User;
use App\Permission;
use App\Project;

class ProjectsController extends Controller
{
    public function index(){
        $projects = Project::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,25);
        $perm_btn =Permission::permBtns($profile,25);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.project.projects', compact('projects','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $project = Project::where('id',$id)->first();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$project]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $project = new Project;
        $project->name = $request->name;
        $project->save();
        $projects = Project::get();
        return response()->json(["status"=>true, "message"=>"Proyecto Creado", "data"=>$projects]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Proyecto Actualizado"]);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        return response()->json(['status'=>true, "message"=>"Proyecto eliminado"]);
    }
}
