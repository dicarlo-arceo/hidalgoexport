<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Permission;

class UsersController extends Controller
{
    public function index(){
        $users = User::get();
        $profiles = Profile::pluck('name','id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile,3);
        $perm_btn =Permission::permBtns($profile,3);
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.users.user', compact('profiles','users','perm_btn'));
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
        $user->save();
        return response()->json(['status'=>true, 'message'=>'Usuario Creado']);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        if($request->password == null)
        {
            $user = User::where('id',$request->id)
            ->update(['email'=>$request->email,
            'name'=>$request->name,'firstname'=>$request->firstname,'lastname'=>$request->lastname,
            'cellphone'=>$request->cellphone,'fk_profile'=>$request->fk_profile]);
        }
        else
        {
            $user = User::where('id',$request->id)
            ->update(['email'=>$request->email,'password'=>bcrypt($request->password),
            'name'=>$request->name,'firstname'=>$request->firstname,'lastname'=>$request->lastname,
            'cellphone'=>$request->cellphone,'fk_profile'=>$request->fk_profile]);
        }
        // dd($codes_edit);
        return response()->json(['status'=>true, 'message'=>"Usuario Actualizado"]);

    }
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['status'=>true, "message"=>"Usuario eliminado"]);

    }
}
