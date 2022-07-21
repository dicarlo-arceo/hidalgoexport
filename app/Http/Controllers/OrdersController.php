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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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
        // dd($profile);
        if($profile != 61)
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->whereNull('Orders.deleted_at')->get();
            // dd($orders);
        }
        else
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where("fk_user","=",User::user_id())
            ->whereNull('Orders.deleted_at')->get();
        }
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
        $flag = 0;
        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Orders',"Orders.id","=","fk_order")
        ->join('Status',"Status.id","=","fk_status")
        ->where('fk_order',$id)
        ->whereNull('Items.deleted_at')->get();
        // dd($items);
        if(count($items) == 0)
        {
            // dd("entre");
            $flag = 1;
            $items = DB::table('Orders')->select("*")
                ->where('id',$id)
                ->whereNull('deleted_at')->get();
        }
        return response()->json(['status'=>true, "data"=>$items, "flag"=>$flag]);
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
        'fk_project'=>$request->fk_project,'address'=>$request->address,'designer'=>$request->designer]);

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
        $status = Item::where('id',$request->id)->first();
        // dd($status);
        $status->fk_status = $request->status;
        $status->commentary=$request->commentary;
        $status->delivery_date=$request->delivery_date;
        // dd($request);

        if($request->hasFile("imagen")){

            $imagen = $request->file("imagen");
            $nombreimagen = "Item_".Str::slug($request->id)."_Orden_".Str::slug($request->idOrder).".".$imagen->guessExtension();
            $ruta = public_path("img/itemsStatus/");

            //$imagen->move($ruta,$nombreimagen);
            copy($imagen->getRealPath(),$ruta.$nombreimagen);

            $status->image = $nombreimagen;

        }
        // dd($status);

        $status->save();

        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Status',"Status.id","=","fk_status")
        ->join('Orders',"Orders.id","=","fk_order")
        ->where('fk_order',$request->idOrder)
        ->whereNull('Items.deleted_at')->get();
        // dd($items);
        // return;
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
    public function updateTR(Request $request)
    {
        // dd($request);
        $item = Item::where('id',$request->id)
        ->update(['tr'=>$request->tr]);

        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Status',"Status.id","=","fk_status")
        ->join('Orders',"Orders.id","=","fk_order")
        ->where('fk_order',$request->fk_order)
        ->whereNull('Items.deleted_at')->get();

        // dd($items);
        return response()->json(['status'=>true, 'message'=>"Orden Actualizada", "data"=>$items]);
    }
    public function updateOrder(Request $request)
    {
        // dd($request);
        if($request->flag == 1)
        {
            $order = Order::where('id',$request->id)
            ->update(['exc_rate'=>$request->exc_rate]);
        }
        else if($request->flag == 2)
        {
            $order = Order::where('id',$request->id)
            ->update(['percentage'=>$request->percentage]);
        }
        else if ($request->flag == 3)
        {
            $order = Order::where('id',$request->id)
            ->update(['expenses'=>$request->expenses]);
        }
        else if ($request->flag == 4)
        {
            $order = Order::where('id',$request->id)
            ->update(['currency'=>$request->currency]);
        }

        // dd($items);
        return response()->json(['status'=>true, 'message'=>"Orden Actualizada"]);
    }
    public function deleteFile(Request $request)
    {
        $image_path = public_path()."/img/itemsStatus/".$request->imgName;
        if (File::exists($image_path)) {
            File::delete($image_path);
            // unlink($image_path);
        }
        $item = Item::where('id',$request->id)->first();
        $item->image = null;
        $item->save();

        return response()->json(['status'=>true, 'message'=>"Imagen Eliminada"]);
    }
}