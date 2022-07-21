<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\User;
use App\Permission;
use App\Project;
use App\Order;
use App\Status;
use DB;

class ProjectsController extends Controller
{
    public function index(){
        $projectsTable = Project::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,25);
        $projects = Project::pluck('name','id');
        $perm_btn =Permission::permBtns($profile,25);
        $cmbStatus = Status::select('id','name')
        ->where("fk_section","27")
        ->pluck('name','id');
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.project.projects', compact('projectsTable','projects','perm_btn','profile','cmbStatus'));
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
    public function GetInfoOrder($id)
    {
        $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where('fk_project',"=",$id)
            ->whereNull('Orders.deleted_at')->get();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$orders]);
    }
}
