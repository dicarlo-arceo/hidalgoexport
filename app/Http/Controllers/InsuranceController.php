<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Insurance;
use App\User;
use App\Permission;
use App\Profile;

class InsuranceController extends Controller
{
    public function index(){
        $insurances = Insurance::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,6);
        $perm_btn =Permission::permBtns($profile,6);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.insurance.insurances', compact('insurances','perm_btn'));
        }
    }

    public function GetInfo($id)
    {
        $insurance = Insurance::where('id',$id)->first();
        return response()->json(['status'=>true, "data"=>$insurance]);
    }

    public function store(Request $request)
    {
        $insurance = new Insurance;
        $insurance->name = $request->name;
        $insurance->save();
        return response()->json(["status"=>true, "message"=>"Aseguradora creada"]);
    }

    public function update(Request $request, $id)
    {
        $insurance = Insurance::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Aseguradora actualizada"]);
    }

    public function destroy($id)
    {
        $insurance = Insurance::find($id);
        $insurance->delete();
        return response()->json(['status'=>true, "message"=>"Aseguradora eliminada"]);
    }
}
