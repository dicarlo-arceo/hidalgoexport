<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charge;
use App\Permission;
use App\User;

class ChargeController extends Controller
{
    public function index ()
    {
        $charges = Charge::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,11);
        $perm_btn =Permission::permBtns($profile,11);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.charge.charges', compact('charges','perm_btn'));
        }
    }
    // cambiar modelo de seguro a Conducto de Cobro
    public function GetInfo($id)
    {
        $charge = Charge::where('id',$id)->first();
        return response()->json(['status'=>true, "data"=>$charge]);
    }

    public function store(Request $request)
    {
        $charge = new Charge;
        $charge->name = $request->name;
        $charge->save();
        return response()->json(["status"=>true, "message"=>"Conducto de Cobro creado"]);
    }

    public function update(Request $request, $id)
    {
        $charge = Charge::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Conducto de Cobro actualizado"]);
    }

    public function destroy($id)
    {
        $charge = Charge::find($id);
        $charge->delete();
        return response()->json(['status'=>true, "message"=>"Conducto de Cobro eliminado"]);
    }
}
