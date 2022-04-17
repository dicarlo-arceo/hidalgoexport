<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Permission;
use App\User;
use App\Nuc;

class ClientsController extends Controller
{
    public function index(){
        $clients = Client::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,5);
        $perm_btn =Permission::permBtns($profile,5);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.client.clients', compact('clients','perm_btn'));
        }
    }

    public function GetInfo($id)
    {
        $client = Client::where('id',$id)->first();
        // dd($user);
        return response()->json(['status'=>true, "data"=>$client]);

    }

    public function store(Request $request)
    {
        // dd($request->all());
        $client = new Client;
        $client->name = $request->name;
        $client->firstname = $request->firstname;
        $client->lastname = $request->lastname;
        $client->birth_date = $request->birth_date;
        $client->rfc = $request->rfc;
        $client->curp = $request->curp;
        // $client->gender = $request->gender;
        // $client->marital_status = $request->marital_status;
        // $client->street = $request->street;
        // $client->e_num = $request->e_num;
        // $client->i_num = $request->i_num;
        // $client->pc = $request->pc;
        // $client->suburb = $request->suburb;
        // $client->country = $request->country;
        // $client->state = $request->state;
        // $client->city = $request->city;
        $client->cellphone = $request->cellphone;
        $client->email = $request->email;
        $client->save();
        return response()->json(["status"=>true, "message"=>"Cliente Creada"]);
    }

    public function update(Request $request)
    {
        $client = Client::where('id',$request->id)
        ->update(['name'=>$request->name, 'firstname'=>$request->firstname,'lastname'=>$request->lastname,
            'birth_date'=>$request->birth_date, 'rfc'=>$request->rfc,'curp'=>$request->curp,
            'cellphone'=>$request->cellphone,'email'=>$request->email]);
            // 'gender'=>$request->gender, 'marital_status'=>$request->marital_status,'street'=>$request->street,
            // 'e_num'=>$request->e_num, 'i_num'=>$request->i_num,'pc'=>$request->pc,
            // 'suburb'=>$request->suburb, 'country'=>$request->country,'state'=>$request->state,
            // 'city'=>$request->city,
        return response()->json(['status'=>true, 'message'=>"Cliente Actualizado"]);

    }

    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();
        return response()->json(['status'=>true, "message"=>"cliente eliminado"]);

    }
    public function SaveNuc(Request $request)
    {
        $nuc = new Nuc;
        $nuc->nuc = $request->nuc;
        $nuc->currency = $request->selectCurrency;
        $nuc->fk_client = $request->fk_client;
        $nuc->estatus = $request->estatus;
        $nuc->save();
        return response()->json(["status"=>true, "message"=>"Nuc creado"]);
    }
}
