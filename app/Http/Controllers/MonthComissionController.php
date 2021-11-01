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
        $users = User::get();
        $nucs = DB::table('Nuc')
        ->select('Nuc.id as id',DB::raw('CONCAT(Client.name," ",firstname," ",lastname) AS name'),"nuc",'Status.id as statId','Status.name as estatus','color')
        ->join('Client',"Client.id","=","Nuc.fk_client")
        ->join('Status',"Nuc.estatus","=","Status.id")
        ->get();
        $clients = DB::table('Nuc')->select('Client.id',DB::raw('CONCAT(Client.name," ",firstname," ",lastname) AS name'))
        ->join('Client',"Client.id","=","Nuc.fk_client")
        ->pluck('name','id');
        $perm = Permission::permView($profile,21);
        $perm_btn =Permission::permBtns($profile,21);
        $cmbStatus = Status::select('id','name')
        ->where("fk_section","19")
        ->pluck('name','id');
        // dd($clients);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('funds.monthcomission.monthcomission', compact('users','perm_btn','cmbStatus','clients'));
        }
    }
}
