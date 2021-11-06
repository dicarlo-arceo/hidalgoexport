<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Permission;
use App\User;

class AssigmentController extends Controller
{
    public function index(){
        $users = User::get();
        $clients = Client::whereNull("fk_agent")->pluck('name','id');
        // dd($clients);
        $profile = User::findProfile();
        $perm = Permission::permView($profile,20);
        $perm_btn =Permission::permBtns($profile,20);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.assigment.assigment', compact('clients','perm_btn','users'));
        }
    }

    public function Viewclients($id){
        // dd($id);
        $clients = Client::where('fk_agent',$id)->get();
        // dd($clients);
        return response()->json(['status'=>true, "data"=>$clients]);
    }

    public function updateClient(Request $request){
        // dd($request->all());
        $client = Client::where('id',$request->client)->first();
        // dd($client);
        $client->fk_agent = $request->id;
        $client->save();
        return response()->json(['status'=>true, "message"=>"Cliente asignado"]);
    }

}
