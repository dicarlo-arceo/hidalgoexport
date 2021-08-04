<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use App\Permission;
use App\Insurance;
use App\Refund;
use DB;

class RefundsController extends Controller
{
    public function index()
    {
        $refunds = DB::table("Status")
        ->select('Status.id as statId','Status.name as statName','Refunds.id as id','folio','color',
        'Insurance.name as insurance','users.name as agent')
        ->join('Refunds','Refunds.fk_status','=','Status.id')
        ->join('Insurance','Insurance.id','=','Refunds.fk_insurance')
        ->join('users','users.id','=','Refunds.fk_agent')
        ->get();
        // dd($initials);
        $agents = User::select('id', DB::raw('CONCAT(name," ",firstname) AS name'))->where("fk_profile","12")->pluck('name','id');
        $insurances = Insurance::pluck('name','id');
        $cmbStatus = Status::select('id','name')
        ->where("fk_section","17")
        ->pluck('name','id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile,17);
        $perm_btn =Permission::permBtns($profile,17);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.OT.refunds.refunds',
            compact('refunds','agents','insurances','perm_btn','cmbStatus'));
        }
    }
    public function GetInfo($id)
    {
        $refund = Refund::where('id',$id)->first();
        // dd($service);
        return response()->json(['status'=>true, "data"=>$refund]);
    }

    public function store(Request $request)
    {
        $refund = new Refund;
        $refund->fk_agent = $request->agent;
        $refund->folio = $request->folio;
        $refund->contractor = $request->contractor;
        $refund->fk_insurance = $request->fk_insurance;
        $refund->entry_date = $request->entry_date;
        $refund->policy = $request->policy;
        $refund->insured = $request->insured;
        $refund->sinister = $request->sinister;
        $refund->amount = $request->amount;
        $refund->payment_form = $request->payment_form;
        $refund->save();
        return response()->json(["status"=>true, "message"=>"Reembolso creado"]);
    }

    public function update(Request $request, $id)
    {
        $refund = Refund::where('id',$request->id)->
        update(['fk_agent'=>$request->agent,
        'fk_agent' => $request->agent,
        'folio' => $request->folio,
        'contractor' => $request->contractor,
        'fk_insurance' => $request->fk_insurance,
        'entry_date' => $request->entry_date,
        'policy' => $request->policy,
        'insured' => $request->insured,
        'sinister' => $request->sinister,
        'amount' => $request->amount,
        'payment_form' => $request->payment_form]);
        return response()->json(['status'=>true, 'message'=>"Reembolso actualizado"]);
    }

    public function destroy($id)
    {
        $refund = Refund::find($id);
        $refund->delete();
        return response()->json(['status'=>true, "message"=>"Reembolso eliminado"]);
    }

    public function updateStatus(Request $request)
    {
        $status = Refund::where('id',$request->id)->first();
        // dd($status);
        $status->fk_status = $request->status;
        $status->save();
        return response()->json(['status'=>true, "message"=>"Estatus Actualizado"]);
    }
}
