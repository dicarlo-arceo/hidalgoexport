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
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Controllers\PdfClass;
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
        ->where('Status.id',"!=",8)
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
        if($request->back_order == "") $request->back_order = 0;
        if($request->existence == "") $request->existence = 0;
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
        ->where('Status.id',"!=",8)
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
    public function DeleteItem($id,$idOrder,$flagTR)
    {
        $flag = 0;
        $item = Item::find($id);
        $item->delete();

        $items = $this->GetItemsBack($idOrder,$flagTR);
        if(count($items) == 0)
        {
            // dd("entre");
            $flag = 1;
            $items = DB::table('Orders')->select("*")
                ->where('id',$idOrder)
                ->whereNull('deleted_at')->get();
        }
        // dd($items);

        return response()->json(['status'=>true, "message"=>"Item eliminado", "data"=>$items, "flag"=>$flag]);
    }
    public function updateStatus(Request $request)
    {
        $flag = 0;
        $status = Item::where('id',$request->id)->first();
        // dd($status->description);

        if($request->status == 8 && $status->back_order != 0 && $status->fk_status != 8)
        {
            $item = new Item;
            $item->fk_order = $status->fk_order;
            $item->store = $status->store;
            $item->item_number = $status->item_number;
            $item->description = $status->description;
            $item->back_order = $status->back_order;
            $item->existence = 0;
            $item->fk_status = 6;
            $item->net_price = 0;
            $item->total_price = 0;
            $item->save();
        }

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

        $items = $this->GetItemsBack($request->idOrder,$request->flagTR);
        if(count($items) == 0)
        {
            // dd("entre");
            $flag = 1;
            $items = DB::table('Orders')->select("*")
                ->where('id',$request->idOrder)
                ->whereNull('deleted_at')->get();
        }
        // dd($request->flagTR);
        // return;
        return response()->json(['status'=>true, "message"=>"Estatus Actualizado", "data"=>$items,"flag"=>$flag]);
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
        if($request->back_order == "") $request->back_order = 0;
        if($request->existence == "") $request->existence = 0;
        // dd($request->back_order,$request->existence);
        $item = Item::where('id',$request->id)
        ->update(['store'=>$request->store,'item_number'=>$request->item_number,'description'=>$request->description,
        'back_order'=>$request->back_order,'existence'=>$request->existence,'net_price'=>$request->net_price,
        'total_price'=>$request->total_price]);

        $items = $this->GetItemsBack($request->fk_order,$request->flagTR);

        // dd($items);
        return response()->json(['status'=>true, 'message'=>"Orden Actualizada", "data"=>$items]);
    }
    public function updateTR(Request $request)
    {
        // dd($request);
        $item = Item::where('id',$request->id)
        ->update(['tr'=>$request->tr]);

        $items = $this->GetItemsBack($request->fk_order,$request->flagTR);
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
    public function GetinfoTR($id)
    {
        $items = DB::table('Items')->select("tr")
        ->where('fk_order',$id)
        ->where('fk_status',"=",8)
        ->whereNull('Items.deleted_at')
        ->groupBy('tr')->get();
        // dd($items);
        // dd($initial->commentary);
        return response()->json(['status'=>true, "data"=>$items]);
    }
    public function GetItemsTR($id,$tr)
    {
        if($tr == 0)
        {
            $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
            ->join('Orders',"Orders.id","=","fk_order")
            ->join('Status',"Status.id","=","fk_status")
            ->where('fk_order',$id)
            ->where('Status.id',"=",8)
            ->whereNull('Items.deleted_at')->get();
        }
        else
        {
            $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
            ->join('Orders',"Orders.id","=","fk_order")
            ->join('Status',"Status.id","=","fk_status")
            ->where('fk_order',$id)
            ->where('Status.id',"=",8)
            ->where('tr',"=",$tr)
            ->whereNull('Items.deleted_at')->get();
        }
        // dd($items);
        // dd($initial->commentary);
        return response()->json(['status'=>true, "data"=>$items]);
    }
    public function GetItemsBack($order,$tr)
    {
        if($tr == null || $tr == "null")
        {
            // dd("entre a null");
            $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
            ->join('Status',"Status.id","=","fk_status")
            ->join('Orders',"Orders.id","=","fk_order")
            ->where('fk_order',$order)
            ->where('Status.id',"!=",8)
            ->whereNull('Items.deleted_at')->get();
        }
        else
        {
            if($tr == 0)
            {
                $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
                ->join('Orders',"Orders.id","=","fk_order")
                ->join('Status',"Status.id","=","fk_status")
                ->where('fk_order',$order)
                ->where('Status.id',"=",8)
                ->whereNull('Items.deleted_at')->get();
            }
            else
            {
                $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
                ->join('Orders',"Orders.id","=","fk_order")
                ->join('Status',"Status.id","=","fk_status")
                ->where('fk_order',$order)
                ->where('Status.id',"=",8)
                ->where('tr',"=",$tr)
                ->whereNull('Items.deleted_at')->get();
            }
        }
        return $items;
    }
    public function GetPDF($id,$cellar,$comition,$dlls,$date,$pkgs)
    {
        $order = DB::table('Orders')->select("Projects.name as projectName","address",'cellphone',"order_number")
                ->join('Projects',"Projects.id","=","fk_project")
                ->join('users',"users.id","=","fk_user")
                ->where('Orders.id',"=",$id)->first();
        $pdf = new PDF();
        $pdf->PrintChapter($order,$cellar,$comition,$dlls,$date,$pkgs);
        $pdf->Output('D',"Orden_".$order->order_number.".pdf");
        return;
    }
    public function ItemsPDF($order,$tr)
    {
        $items = $this->GetItemsBack($order,$tr);
        $pdf = new PDFItems();
        $pdf->PrintPDF($items);
        $orderName = Order::select("order_number")->where('id',$order)->first();
        // dd($orderName);
        $pdf->Output('D',"Items_de_orden_".$orderName->order_number.".pdf");
        return;
    }
}
