<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use App\Permission;
use App\Initial;
use App\Currency;
use App\Insurance;
use App\Paymentform;
use App\Charge;
use App\Branch;
use App\Application;
use DB;
use Carbon\Carbon;

class InitialController extends Controller
{
    public function index()
    {
        // $initials = Initial::get();
        $initials = DB::table("Status")
        ->select('Status.id as statId','Status.name as name','Initials.id as id', 'rfc','client','color',
        'Insurance.name as insurance','Branch.name as branch', 'users.name as agent','Initials.created_at as date')
        ->join('Initials','Initials.fk_status','=','Status.id')
        ->join('Insurance','Insurance.id','=','Initials.fk_insurance')
        ->join('Branch','Branch.id','=','Initials.fk_branch')
        ->join('users','users.id','=','Initials.fk_agent')
        ->where('Status.id','<>','4')
        ->get();
        // dd($initials);
        $agents = User::select('id', DB::raw('CONCAT(name," ",firstname) AS name'))->where("fk_profile","12")->pluck('name','id');
        $currencies = Currency::pluck('name','id');
        $insurances = Insurance::pluck('name','id');
        $paymentForms = Paymentform::pluck('name','id');
        $charges = Charge::pluck('name','id');
        $branches = Branch::pluck('name','id');
        $applications = Application::pluck('name','id');
        $cmbStatus = Status::select('id','name')
        ->where("fk_section","14")
        ->pluck('name','id');
        // $status = DB::table("Status")
        //     ->select('Status.id as id','Status.name as name')
        //     ->join('Initials','Initials.fk_status','=','Status.id')
        //     ->get();
            // dd($status);
        $profile = User::findProfile();
        $perm = Permission::permView($profile,14);
        $perm_btn =Permission::permBtns($profile,14);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.OT.Initials.initial',
            compact('initials','agents','currencies','insurances','paymentForms','charges','branches','applications','perm_btn','cmbStatus'));
        }
    }
    public function GetInfo($id)
    {
        $initial = Initial::where('id',$id)->first();
        // dd($initial);
        return response()->json(['status'=>true, "data"=>$initial]);
    }

    public function store(Request $request)
    {
        $initial = new Initial;
        $initial->fk_agent = $request->agent;
        $initial->client = $request->client;
        $initial->rfc = $request->rfc;
        $initial->promoter_date = $request->promoter;
        $initial->system_date = $request->system;
        $initial->folio = $request->folio;
        $initial->fk_insurance = $request->insurance;
        $initial->fk_branch = $request->branch;
        $initial->fk_application = $request->application;
        $initial->pna = $request->pna;
        $initial->fk_payment_form = $request->paymentForm;
        $initial->fk_currency = $request->currency;
        $initial->fk_charge = $request->charge;
        $initial->save();
        return response()->json(["status"=>true, "message"=>"Inicial creada"]);
    }

    public function update(Request $request, $id)
    {
        $initial = Initial::where('id',$request->id)->
        update(['fk_agent'=>$request->agent,
        'client' => $request->client,
        'rfc' => $request->rfc,
        'promoter_date' => $request->promoter,
        'system_date' => $request->system,
        'folio' => $request->folio,
        'fk_insurance' => $request->insurance,
        'fk_branch' => $request->branch,
        'fk_application' => $request->application,
        'pna' => $request->pna,
        'fk_payment_form' => $request->paymentForm,
        'fk_currency' => $request->currency,
        'fk_charge' => $request->charge]);
        return response()->json(['status'=>true, 'message'=>"Inicial actualizada"]);
    }

    public function destroy($id)
    {
        $initial = Initial::find($id);
        $initial->delete();
        return response()->json(['status'=>true, "message"=>"Inicial eliminado"]);
    }

    public function updateStatus(Request $request)
    {
        $status = Initial::where('id',$request->id)->first();
        // dd($status);
        $status->fk_status = $request->status;
        $status->save();
        return response()->json(['status'=>true, "message"=>"Estatus Actualizado"]);
    }
}
