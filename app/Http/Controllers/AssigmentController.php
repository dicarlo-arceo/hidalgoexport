<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Permission;
use App\User;

class AssigmentController extends Controller
{
    public function index(){
        $clients = Client::get();
        $users = User::pluck('name','id');
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
}
