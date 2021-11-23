<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Permission;
use App\MonthFund;
use App\Nuc;
use App\Status;
use App\Exports\ExportFund;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class MonthFundsController extends Controller
{
    public function index(){
        $profile = User::findProfile();
        $nucs = DB::table('Nuc')
        ->select('Nuc.id as id',DB::raw('CONCAT(Client.name," ",firstname," ",lastname) AS name'),"nuc",'Status.id as statId','Status.name as estatus','color')
        ->join('Client',"Client.id","=","Nuc.fk_client")
        ->join('Status',"Nuc.estatus","=","Status.id")
        ->get();
        $clients = DB::table('Nuc')->select('Client.id',DB::raw('CONCAT(Client.name," ",firstname," ",lastname) AS name'))
        ->join('Client',"Client.id","=","Nuc.fk_client")
        ->pluck('name','id');
        $perm = Permission::permView($profile,19);
        $perm_btn =Permission::permBtns($profile,19);
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
            return view('funds.monthfund.monthfunds', compact('nucs','perm_btn','cmbStatus','clients'));
        }
    }
    public function GetInfo($id)
    {
        $movimientos = DB::table('Month_fund')->select("*","Month_fund.id as id",DB::raw('IFNULL(auth_date, "-") as auth'))->join('Nuc',"Nuc.id","=","fk_nuc")->where('fk_nuc',$id)->whereNull('Month_fund.deleted_at')->get();
        return response()->json(['status'=>true, "data"=>$movimientos]);
    }

    public function GetInfoLast($id)
    {
        $movimientos = DB::table('Month_fund')->select("new_balance")->join('Nuc',"Nuc.id","=","fk_nuc")->where('fk_nuc',$id)->orderby("apply_date","DESC")->orderby("Month_fund.id","DESC")->whereNull('Month_fund.deleted_at')->first();
        return response()->json(['status'=>true, "data"=>$movimientos]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $fund = new MonthFund;
        $fund->fk_nuc = $request->fk_nuc;
        $fund->type = $request->type;
        $fund->amount = $request->amount;
        $fund->prev_balance = $request->prev_balance;
        $fund->new_balance = $request->new_balance;
        $fund->apply_date = $request->apply_date;
        $day = explode("-", $request->apply_date);
        $day = intval($day[2]);
        // dd($day);
        $fund->save();

        if($request->type == "Apertura")
        {
            $nuc = Nuc::where('id', $request->fk_nuc)->first();
            if($day <= 15)
            {
                $nuc->cut_date = 15;
            }
            else
            {
                $nuc->cut_date = 30;
            }
            $nuc->save();
        }

        $movimientos = DB::table('Month_fund')->select("*","Month_fund.id as id",DB::raw('IFNULL(auth_date, "-") as auth'))->join('Nuc',"Nuc.id","=","fk_nuc")->where('fk_nuc',$request->fk_nuc)->whereNull('Month_fund.deleted_at')->get();
        return response()->json(["status"=>true, "message"=>"Movimiento Registrado", "data"=>$movimientos]);
    }
    public function destroy($id)
    {
        $fund = MonthFund::find($id);
        // dd($fund);
        $fk_nuc = $fund->fk_nuc;
        $fund->delete();
        $movimientos = DB::table('Month_fund')->select("*","Month_fund.id as id",DB::raw('IFNULL(auth_date, "-") as auth'))->join('Nuc',"Nuc.id","=","fk_nuc")->where('fk_nuc',$fk_nuc)->whereNull('Month_fund.deleted_at')->get();
        return response()->json(['status'=>true, "message"=>"Movimiento eliminado", "data"=>$movimientos]);

    }
    public function updateStatus(Request $request)
    {
        $status = Nuc::where('id',$request->id)->first();
        // dd($status);
        $status->estatus = $request->status;
        $status->save();
        return response()->json(['status'=>true, "message"=>"Estatus Actualizado"]);
    }
    public function updateAuth(Request $request)
    {
        $auth = MonthFund::where('id',$request->id)->first();
        // dd($status);
        $auth->auth_date = $request->auth;
        $auth->save();
        $movimientos = DB::table('Month_fund')->select("*","Month_fund.id as id",DB::raw('IFNULL(auth_date, "-") as auth'))->join('Nuc',"Nuc.id","=","fk_nuc")->where('fk_nuc',$auth->fk_nuc)->whereNull('deleted_at')->get();
        return response()->json(["status"=>true, "message"=>"Movimiento Autorizado", "data"=>$movimientos]);
    }
    public function GetNuc($id)
    {
        $nuc = Nuc::where('id',$id)->first();
        return response()->json(["status"=>true, "data"=>$nuc]);
    }
    public function update(Request $request, $id)
    {
        $nuc = Nuc::where('id',$request->id)->update(['nuc'=>$request->nuc,'fk_client'=>$request->fk_client]);
        return response()->json(['status'=>true, 'message'=>"Nuc Actualizado"]);
    }
    public function ExportFunds($id)
    {
        $nuc = DB::table('Nuc')->select('nuc',DB::raw('CONCAT(Client.name," ",firstname," ",lastname) AS name'))->join('Client',"Nuc.fk_client","=","Client.id")->where('Nuc.id',$id)->first();
        $nombre = "NUC_".(string)$nuc->nuc."_".$nuc->name.".xlsx";
        return Excel::download(new ExportFund($id),$nombre);
    }
}
