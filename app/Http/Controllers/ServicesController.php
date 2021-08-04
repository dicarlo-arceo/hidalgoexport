<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use App\Permission;
use App\Service;
use App\Branch;
use App\Insurance;
use DB;

class ServicesController extends Controller
{
    public function index()
    {
        $services = DB::table("Status")
        ->select('Status.id as statId','Status.name as statName','Services.id as id','Services.name as name','folio','type','color',
        'Insurance.name as insurance','Branch.name as branch', 'users.name as agent')
        ->join('Services','Services.fk_status','=','Status.id')
        ->join('Insurance','Insurance.id','=','Services.fk_insurance')
        ->join('Branch','Branch.id','=','Services.fk_branch')
        ->join('users','users.id','=','Services.fk_agent')
        ->get();
        // dd($initials);
        $agents = User::select('id', DB::raw('CONCAT(name," ",firstname) AS name'))->where("fk_profile","12")->pluck('name','id');
        $insurances = Insurance::pluck('name','id');
        $branches = Branch::pluck('name','id');
        $cmbStatus = Status::select('id','name')
        ->where("fk_section","16")
        ->pluck('name','id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile,16);
        $perm_btn =Permission::permBtns($profile,16);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.OT.services.service',
            compact('services','agents','insurances','branches','perm_btn','cmbStatus'));
        }
    }
    public function GetInfo($id)
    {
        $service = Service::where('id',$id)->first();
        // dd($service);
        return response()->json(['status'=>true, "data"=>$service]);
    }

    public function store(Request $request)
    {
        $service = new Service;
        $service->fk_agent = $request->agent;
        $service->entry_date = $request->entry_date;
        $service->policy = $request->policy;
        $service->response_date = $request->response_date;
        $service->download = $request->download;
        $service->type = $request->type;
        $service->folio = $request->folio;
        $service->name = $request->name;
        $service->record = $request->record;
        $service->fk_insurance = $request->insurance;
        $service->fk_branch = $request->branch;
        $service->save();
        return response()->json(["status"=>true, "message"=>"Servicio creado"]);
    }

    public function update(Request $request, $id)
    {
        $service = Service::where('id',$request->id)->
        update(['fk_agent'=>$request->agent,
        'entry_date' => $request->entry_date,
        'policy' => $request->policy,
        'response_date' => $request->response_date,
        'download' => $request->download,
        'type' => $request->type,
        'folio' => $request->folio,
        'name' => $request->name,
        'record' => $request->record,
        'fk_insurance' => $request->insurance,
        'fk_branch' => $request->branch]);
        return response()->json(['status'=>true, 'message'=>"Servicio actualizado"]);
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        $service->delete();
        return response()->json(['status'=>true, "message"=>"Servicio eliminado"]);
    }

    public function updateStatus(Request $request)
    {
        $status = Service::where('id',$request->id)->first();
        // dd($status);
        $status->fk_status = $request->status;
        $status->save();
        return response()->json(['status'=>true, "message"=>"Estatus Actualizado"]);
    }
}
