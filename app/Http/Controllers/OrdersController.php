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
use DB;

class OrdersController extends Controller
{
    public function index(){
        // $orders = Order::get();
        $profile = User::findProfile();
        $projects = Project::pluck('name','id');
        $perm = Permission::permView($profile,27);
        $perm_btn =Permission::permBtns($profile,27);
        $cmbStatus = Status::select('id','name')
        ->where("fk_section","27")
        ->pluck('name','id');
        if($profile != 61)
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(users.name," ",users.firstname," ",users.lastname) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->whereNull('Orders.deleted_at')->get();
        }
        else
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(users.name," ",users.firstname," ",users.lastname) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where("fk_user","=",User::user_id())
            ->whereNull('Orders.deleted_at')->get();
        }
        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.order.orders', compact('orders','perm_btn','profile','projects','cmbStatus'));
        }
    }
    public function GetInfo($id)
    {
        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Orders',"Orders.id","=","fk_order")
        ->join('Status',"Status.id","=","fk_status")
        ->where('fk_order',$id)
        ->whereNull('Items.deleted_at')->get();
        return response()->json(['status'=>true, "data"=>$items]);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $item = new Item;
        $item->fk_order = $request->fk_order;
        $item->store = $request->store;
        $item->item_number = $request->item_number;
        $item->description = $request->description;
        $item->back_order = $request->back_order;
        $item->existence = $request->existence;
        $item->fk_status = 6;
        $item->net_price = $request->net_price;
        $item->total_price = $request->total_price;
        $item->save();

        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Status',"Status.id","=","fk_status")
        ->join('Orders',"Orders.id","=","fk_order")
        ->where('fk_order',$request->fk_order)
        ->whereNull('Items.deleted_at')->get();
        return response()->json(["status"=>true, "message"=>"Item Registrado", "data"=>$items]);
    }
    public function GetInfoOrder($id)
    {
        $order = Order::where('id',$id)->first();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$order]);
    }
    public function update(Request $request)
    {
        $order = Order::where('id',$request->id)
        ->update(['order_number'=>$request->order_number,
        'fk_project'=>$request->fk_project,'address'=>$request->address]);

        return response()->json(['status'=>true, 'message'=>"Orden Actualizada"]);
    }
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return response()->json(['status'=>true, "message"=>"Orden eliminado"]);
    }
    public function DeleteItem($id,$idOrder)
    {
        $item = Item::find($id);
        $item->delete();
        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Status',"Status.id","=","fk_status")
        ->join('Orders',"Orders.id","=","fk_order")
        ->where('fk_order',$idOrder)
        ->whereNull('Items.deleted_at')->get();
        return response()->json(['status'=>true, "message"=>"Item eliminado", "data"=>$items]);
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        $status = Item::where('id',$request->id)->first();
        // dd($status);
        $status->fk_status = $request->status;
        $status->commentary=$request->commentary;
        $status->save();

        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Status',"Status.id","=","fk_status")
        ->join('Orders',"Orders.id","=","fk_order")
        ->where('fk_order',$request->idOrder)
        ->whereNull('Items.deleted_at')->get();
        return response()->json(['status'=>true, "message"=>"Estatus Actualizado", "data"=>$items]);
    }

    public function GetinfoStatus($id)
    {
        $item = Item::where('id',$id)->first();
        // dd($initial->commentary);
        return response()->json(['status'=>true, "data"=>$item]);
    }
    public function GetInfoItem($id)
    {
        $item = Item::where('id',$id)->first();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$item]);
    }
    public function updateItem(Request $request)
    {
        // dd($request);
        $item = Item::where('id',$request->id)
        ->update(['store'=>$request->store,'item_number'=>$request->item_number,'description'=>$request->description,
        'back_order'=>$request->back_order,'existence'=>$request->existence,'net_price'=>$request->net_price,
        'total_price'=>$request->total_price]);

        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Status',"Status.id","=","fk_status")
        ->join('Orders',"Orders.id","=","fk_order")
        ->where('fk_order',$request->fk_order)
        ->whereNull('Items.deleted_at')->get();

        // dd($items);
        return response()->json(['status'=>true, 'message'=>"Orden Actualizada", "data"=>$items]);
    }
}
