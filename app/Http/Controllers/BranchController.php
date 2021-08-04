<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Permission;
use App\User;

class BranchController extends Controller
{
    public function index ()
    {
        $branches = Branch::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,10);
        $perm_btn =Permission::permBtns($profile,10);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.branch.branches', compact('branches','perm_btn'));
        }
    }
    // cambiar modelo de seguro a Ramo
    public function GetInfo($id)
    {
        $branch = Branch::where('id',$id)->first();
        return response()->json(['status'=>true, "data"=>$branch]);
    }

    public function store(Request $request)
    {
        $branch = new Branch;
        $branch->name = $request->name;
        $branch->save();
        return response()->json(["status"=>true, "message"=>"Ramo creada"]);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Ramo actualizada"]);
    }

    public function destroy($id)
    {
        $branch = Branch::find($id);
        $branch->delete();
        return response()->json(['status'=>true, "message"=>"Ramo eliminada"]);
    }
}
