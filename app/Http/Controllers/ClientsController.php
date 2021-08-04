<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Enterprise;
use App\Permission;
use App\User;

class ClientsController extends Controller
{
    public function index(){
        $clients = Client::get();
        $enterprises = Enterprise::get();
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
            return view('admin.client.clients', compact('clients','enterprises','perm_btn'));
        }
    }

    public function GetInfo($id)
    {
        $client = Client::where('id',$id)->first();
        // dd($user);
        return response()->json(['status'=>true, "data"=>$client]);

    }
    public function GetInfoE($id)
    {
        $enterprise = Enterprise::where('id',$id)->first();
        // dd($user);
        return response()->json(['status'=>true, "data"=>$enterprise]);

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
        $client->gender = $request->gender;
        $client->marital_status = $request->marital_status;
        $client->street = $request->street;
        $client->e_num = $request->e_num;
        $client->i_num = $request->i_num;
        $client->pc = $request->pc;
        $client->suburb = $request->suburb;
        $client->country = $request->country;
        $client->state = $request->state;
        $client->city = $request->city;
        $client->cellphone = $request->cellphone;
        $client->email = $request->email;
        $client->save();
        return response()->json(["status"=>true, "message"=>"Persona Física Creada"]);
    }

    public function update(Request $request)
    {
        $client = Client::where('id',$request->id)
        ->update(['name'=>$request->name, 'firstname'=>$request->firstname,'lastname'=>$request->lastname,
            'birth_date'=>$request->birth_date, 'rfc'=>$request->rfc,'curp'=>$request->curp,
            'gender'=>$request->gender, 'marital_status'=>$request->marital_status,'street'=>$request->street,
            'e_num'=>$request->e_num, 'i_num'=>$request->i_num,'pc'=>$request->pc,
            'suburb'=>$request->suburb, 'country'=>$request->country,'state'=>$request->state,
            'city'=>$request->city, 'cellphone'=>$request->cellphone,'email'=>$request->email]);
        return response()->json(['status'=>true, 'message'=>"Cliente Actualizado"]);

    }

    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();
        return response()->json(['status'=>true, "message"=>"cliente eliminado"]);

    }

    public function saveEnterprise(Request $request)
    {
        // dd($request->all());
        $enterprise = new Enterprise();
        $enterprise->business_name = $request->business_name;
        $enterprise->date = $request->date;
        $enterprise->rfc = $request->rfc;
        $enterprise->street = $request->street;
        $enterprise->e_num = $request->e_num;
        $enterprise->i_num = $request->i_num;
        $enterprise->pc = $request->pc;
        $enterprise->suburb = $request->suburb;
        $enterprise->country = $request->country;
        $enterprise->state = $request->state;
        $enterprise->city = $request->city;
        $enterprise->cellphone = $request->cellphone;
        $enterprise->email = $request->email;
        $enterprise->name_contact = $request->name_contact;
        $enterprise->phone_contact = $request->phone_contact;
        $enterprise->save();
        return response()->json(["status"=>true, "message"=>"Persona Moral Creada"]);


    }

    public function updateEnterprise(Request $request)
    {
        // dd("entre0");
        // $enterprise = Enterprise::where('id',$request->id)
        // ->update(['business_name'=>$request->business_name,
        //     'date'=>$request->date, 'rfc'=>$request->rfc,'street'=>$request->street,
        //     'e_num'=>$request->e_num, 'i_num'=>$request->i_num,'pc'=>$request->pc,
        //     'suburb'=>$request->suburb, 'country'=>$request->country,'state'=>$request->state,
        //     'city'=>$request->city, 'cellphone'=>$request->cellphone,'email'=>$request->email,
        //     'name_contact'=>$request->name_contact,'phone_contact'=>$request->phone_contact]);
        // return response()->json(['status'=>true, 'message'=>"Empresa Actualizada"]);
        $enterprise = Enterprise::where('id',$request->id)->first();
        $enterprise->business_name = $request->business_name;
        $enterprise->date = $request->date;
        $enterprise->rfc = $request->rfc;
        $enterprise->street = $request->street;
        $enterprise->e_num = $request->e_num;
        $enterprise->i_num = $request->i_num;
        $enterprise->pc = $request->pc;
        $enterprise->suburb = $request->suburb;
        $enterprise->country = $request->country;
        $enterprise->state = $request->state;
        $enterprise->city = $request->city;
        $enterprise->cellphone = $request->cellphone;
        $enterprise->email = $request->email;
        $enterprise->name_contact = $request->name_contact;
        $enterprise->phone_contact = $request->phone_contact;
        $enterprise->save();
        return response()->json(['status'=>true, 'message'=>"Empresa Actualizada"]);

    }

    public function destroyEnterprise($id)
    {
        $enterprise = Enterprise::find($id);
        // dd($enterprise);
        $enterprise->delete();
        return response()->json(['status'=>true, "message"=>"Empresa eliminada"]);
    }
}
