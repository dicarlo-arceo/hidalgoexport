<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Permission;
use App\Enterprise;
use App\Project;
use App\Order;

class ClientsController extends Controller
{
    public function index(){
        $users = User::where('fk_profile','=','61')->get();
        $enterprises = Enterprise::pluck('name','id');
        $projects = Project::pluck('name','id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile,24);
        $perm_btn =Permission::permBtns($profile,24);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.clients.client', compact('users','perm_btn','enterprises','projects'));
        }

    }

    public function GetInfo($id)
    {

        $user = User::where('id',$id)->first();
        // dd($user);
        return response()->json(['status'=>true, "data"=>$user]);

    }

    public function store(Request $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->name = $request->name;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->cellphone = $request->cellphone;
        $user->fk_profile = $request->fk_profile;
        $user->fk_enterprise = $request->fk_enterprise;
        $user->save();
        return response()->json(['status'=>true, 'message'=>'Cliente Creado']);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        if($request->password == null)
        {
            $user = User::where('id',$request->id)
            ->update(['email'=>$request->email,
            'name'=>$request->name,'firstname'=>$request->firstname,'lastname'=>$request->lastname,
            'cellphone'=>$request->cellphone,'fk_enterprise'=>$request->fk_enterprise]);
        }
        else
        {
            $user = User::where('id',$request->id)
            ->update(['email'=>$request->email,'password'=>bcrypt($request->password),
            'name'=>$request->name,'firstname'=>$request->firstname,'lastname'=>$request->lastname,
            'cellphone'=>$request->cellphone,'fk_enterprise'=>$request->fk_enterprise]);
        }
        // dd($codes_edit);
        return response()->json(['status'=>true, 'message'=>"Cliente Actualizado"]);

    }
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['status'=>true, "message"=>"Cliente eliminado"]);

    }
    public function SaveOrder(Request $request)
    {
        $order = new Order;
        $order->order_number = $request->order_number;
        $order->fk_project = $request->fk_project;
        $order->fk_user = $request->fk_user;
        $order->address = $request->address;
        $order->save();
        // dd("entre");
        return response()->json(["status"=>true, "message"=>"Orden creada"]);
    }
}
