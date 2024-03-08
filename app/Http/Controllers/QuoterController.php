<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\User;
use App\Permission;
use App\Order;
use App\Item;
use App\Project;
use App\Status;
use App\Receipt_assigns;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Controllers\PdfClass;
use DB;

class QuoterController extends Controller
{
    public function index(){
        // $orders = Order::get();
        $profile = User::findProfile();
        $projects = Project::orderBy('name')->pluck('name','id');
        $clients = DB::table('users')->select(DB::raw('CONCAT(IFNULL(name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'),'id')->where('fk_profile','=','61')->orderBy('name')->pluck("name",'id');
        $perm = Permission::permView($profile,29);
        $perm_btn =Permission::permBtns($profile,29);
        $cmbStatus = Status::select('id','name')
            ->where("fk_section","29")
            ->pluck('name','id');
        $flagClosed = 0;

        $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
        ->join('users',"fk_user","=","users.id")
        ->join('Projects',"fk_project","=","Projects.id")
        ->where('stat_open','=',0)
        ->where('quote','=',0)
        ->whereNull('Orders.deleted_at')->get();

        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.quoter.quoter', compact('orders','perm_btn','profile','projects','cmbStatus','flagClosed','clients'));
        }
    }
}
