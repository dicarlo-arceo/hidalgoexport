<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\User;
use App\Permission;

class ProfilesController extends Controller
{
    public function index(){
        $profiles = Profile::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,4);
        $perm_btn =Permission::permBtns($profile,4);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.profile.profiles', compact('profiles','perm_btn'));
        }
    }

    public function GetInfo($id)
    {
        $profile = Profile::where('id',$id)->first();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$profile]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $profile = new Profile;
        $profile->name = $request->name;
        $profile->save();
        $profiles = Profile::get();
        return response()->json(["status"=>true, "message"=>"Perfil Creado", "data"=>$profiles]);
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Perfil Actualizado"]);
    }

    public function destroy($id)
    {
        $profile = Profile::find($id);
        $profile->delete();
        return response()->json(['status'=>true, "message"=>"Perfil eliminado"]);
    }
}
