<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enterprise;
use App\Profile;
use App\User;
use App\Permission;

class EnterpriseController extends Controller
{
    public function index(){
        $enterprises = Enterprise::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,23);
        $perm_btn =Permission::permBtns($profile,23);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.enterprise.enterprise', compact('enterprises','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $enterprise = Enterprise::where('id',$id)->first();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$enterprise]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $enterprise = new Enterprise;
        $enterprise->name = $request->name;
        $enterprise->save();
        $enterprises = Enterprise::get();
        return response()->json(["status"=>true, "message"=>"Empresa Creada", "data"=>$enterprises]);
    }

    public function update(Request $request, $id)
    {
        $enterprise = Enterprise::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Empresa Actualizada"]);
    }

    public function destroy($id)
    {
        $enterprise = Enterprise::find($id);
        $enterprise->delete();
        return response()->json(['status'=>true, "message"=>"Empresa eliminada"]);
    }
}
