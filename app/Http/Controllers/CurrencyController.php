<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use App\User;
use App\Permission;
use App\Profile;
class CurrencyController extends Controller
{
    public function index()
    {
        $applications = Currency::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,9);
        $perm_btn =Permission::permBtns($profile,9);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.currency.currencies', compact('applications','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $currency = Currency::where('id',$id)->first();
        return response()->json(['status'=>true, "data"=>$currency]);
    }

    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->name = $request->name;
        $currency->save();
        return response()->json(["status"=>true, "message"=>"Moneda creada"]);
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Moneda actualizada"]);
    }

    public function destroy($id)
    {
        $currency = Currency::find($id);
        $currency->delete();
        return response()->json(['status'=>true, "message"=>"Moneda eliminada"]);
    }
}
