<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Permission;
use App\MonthFund;
use App\Nuc;
use DB;

class MonthFundsController extends Controller
{
    public function index(){
        $profile = User::findProfile();
        $nucs = DB::table('Nuc')
        ->select('Nuc.id as id',DB::raw('CONCAT(name," ",firstname," ",lastname) AS name'),"nuc")
        ->join('Client',"Client.id","=","Nuc.fk_client")
        ->get();
        $perm = Permission::permView($profile,4);
        $perm_btn =Permission::permBtns($profile,4);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('funds.monthfund.monthfunds', compact('nucs','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $movimientos = MonthFund::where('fk_nuc',$id)->get();
        return response()->json(['status'=>true, "data"=>$movimientos]);
    }
}
