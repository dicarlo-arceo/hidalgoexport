<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application;
use App\User;
use App\Permission;
use App\Profile;
class ApplicationsController extends Controller
{
    public function index ()
    {
        $applications = Application::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,8);
        $perm_btn =Permission::permBtns($profile,8);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.applications.application', compact('applications','perm_btn'));
        }
    }
    // cambiar modelo de seguro a solicitud
    public function GetInfo($id)
    {
        $application = Application::where('id',$id)->first();
        return response()->json(['status'=>true, "data"=>$application]);
    }

    public function store(Request $request)
    {
        $application = new Application;
        $application->name = $request->name;
        $application->save();
        return response()->json(["status"=>true, "message"=>"Solicitud creada"]);
    }

    public function update(Request $request, $id)
    {
        $application = Application::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Solicitud actualizada"]);
    }

    public function destroy($id)
    {
        $application = Application::find($id);
        $application->delete();
        return response()->json(['status'=>true, "message"=>"Solicitud eliminada"]);
    }
}
