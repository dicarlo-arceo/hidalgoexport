<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Permission;
use App\MonthlyComission;
use App\Nuc;
use App\Status;
use DB;

class MonthComissionController extends Controller
{
    public function index(){
        $profile = User::findProfile();
        $users = DB::table('users')->select('id',DB::raw('CONCAT(name," ",firstname," ",lastname) AS name'))->whereNull('deleted_at')->get();
        $perm = Permission::permView($profile,21);
        $perm_btn =Permission::permBtns($profile,21);
        // dd($clients);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('funds.monthcomission.monthcomission', compact('users','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        // $movimientos = DB::table('Month_fund')->select("*","Month_fund.id as id",DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS client_name'),
        // DB::raw('IFNULL(auth_date, "-") as auth'))->join('Nuc',"Nuc.id","=","fk_nuc")->join('Client',"Client.id","=","fk_client")->where('fk_agent',$id)->
        // whereNull('Month_fund.deleted_at')->get();
        $clients = DB::table('Nuc')->select("Nuc.id as idNuc","nuc", DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS client_name'))
        ->join('Client',"Client.id","=","fk_client")->where('fk_agent',$id)
        ->get();
        return response()->json(['status'=>true, "data"=>$clients]);
    }
    public function GetInfoMonth($id,$month,$year)
    {
        $movimientos = DB::table('Month_fund')->select("*")->where('fk_nuc',$id)->whereMonth('apply_date',$month)->whereYear('apply_date',$year)->whereNull('deleted_at')->get();
        // dd($movimientos);
        return response()->json(['status'=>true, "data"=>$movimientos]);
    }
    public function GetInfoLast($id)
    {
        // dd($id);
        // $movimientos = DB::table('Month_fund')->select("*","Month_fund.id as id",DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS client_name'),
        // DB::raw('IFNULL(auth_date, "-") as auth'))->join('Nuc',"Nuc.id","=","fk_nuc")->join('Client',"Client.id","=","fk_client")->where('fk_agent',$id)->
        // whereNull('Month_fund.deleted_at')->get();
        // $clients = DB::table('Nuc')->select("Nuc.id as idNuc","nuc", DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS client_name'))
        // ->join('Client',"Client.id","=","fk_client")->where('fk_agent',$id)
        // ->get();
        
        $movements = DB::table('Month_fund')->select('*')->where('fk_nuc',$id)->orderByRaw('id DESC')->first();
        // dd($movements);
        return response()->json(['status'=>true, "data"=>$movements]);
    }
}
