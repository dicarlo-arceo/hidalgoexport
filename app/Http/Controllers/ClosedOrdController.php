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
use App\Globals;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Controllers\PdfClass;
use DB;

class ClosedOrdController extends Controller
{
    public function index(){
        // $orders = Order::get();
        $profile = User::findProfile();
        $projects = Project::pluck('name','id');
        $perm = Permission::permView($profile,28);
        $perm_btn =Permission::permBtns($profile,28);
        $cmbStatus = Status::select('id','name')
            ->where("fk_section","27")
            ->pluck('name','id');
        $title = "Órdenes Cerradas";
        $global_er = Globals::where("id",1)->first();
        $flagClosed = 1;
        // dd($profile);
        if($profile != 61)
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where('stat_open','=',1)
            ->where('quote','=',1)
            ->whereNull('Orders.deleted_at')->get();
            // dd($orders);
        }
        else
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where("fk_user","=",User::user_id())
            ->where('stat_open','=',1)
            ->where('quote','=',1)
            ->whereNull('Orders.deleted_at')->get();
        }
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.order.orders', compact('orders','perm_btn','profile','projects','cmbStatus','title','flagClosed','global_er'));
        }
    }
}
