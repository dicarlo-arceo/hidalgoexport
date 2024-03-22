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

        $orders = DB::table('Orders')->select('Orders.id','quote_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
        ->join('users',"fk_user","=","users.id")
        ->join('Projects',"fk_project","=","Projects.id")
        ->where('stat_open','=',0)
        ->where('quote','=',0)
        ->whereNull('Orders.deleted_at')->get();

        // dd($perm_btn);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.quoter.quoter', compact('orders','perm_btn','profile','projects','cmbStatus','flagClosed','clients'));
        }
    }

    public function SaveOrder(Request $request)
    {
        if($request->idupdate == 0)
        {
            $order = new Order;
            $order->quote_number = $request->quote_number;
            $order->fk_project = $request->fk_project;
            $order->fk_user = $request->fk_user;
            $order->destiny = $request->destiny;
            $order->quote_date = $request->quote_date;
            $order->designer = $request->designer;
            $order->quote = 0;
            $order->save();
            return response()->json(["status"=>true, "message"=>"Cotización creada"]);
        }
        else
        {
            $order = Order::where('id',$request->idupdate)
                ->update(['quote_number'=>$request->quote_number, 'fk_project'=>$request->fk_project, 'fk_user'=>$request->fk_user, 'destiny'=>$request->destiny,
                'quote_date'=>$request->quote_date, 'designer'=>$request->designer]);
            return response()->json(["status"=>true, "message"=>"Cotización actualizada"]);
        }
        // dd("entre");
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
        else if ($request->flag == 5)
        {
            $order = Order::where('id',$request->id)
            ->update(['broker'=>$request->broker]);
        }
        else if ($request->flag == 6)
        {
            $order = Order::where('id',$request->id)
            ->update(['roundout'=>$request->roundout]);
        }

        // dd($items);
        return response()->json(['status'=>true, 'message'=>"Orden Actualizada"]);
    }

    public function ReturnData($id)
    {
        $items = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
        ->join('Orders',"Orders.id","=","fk_order")
        ->join('Status',"Status.id","=","fk_status")
        ->where('fk_order',$id)
        ->where('Status.id',"!=",8)
        ->whereNull('Items.deleted_at')->get();

        $order = DB::table('Orders')->select("*")
            ->where('id',$id)
            ->whereNull('deleted_at')->first();

        return array("order" => $order, "items" => $items);
    }

    public function GetInfo($id)
    {
        $data = $this->ReturnData($id);

        return response()->json(['status'=>true, "data"=>$data["items"], "order"=>$data["order"]]);
    }

    public function storeItem(Request $request)
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

        $data = $this->ReturnData($request->fk_order);

        return response()->json(["status"=>true, "message"=>"Item Registrado", "data"=>$data["items"], "order"=>$data["order"]]);
    }

    public function DeleteItem($id, $idOrder, Request $request)
    {
        $flag = 0;
        $item = Item::find($id);
        $item->delete();

        $data = $this->ReturnData($idOrder);

        return response()->json(['status'=>true, "message"=>"Item eliminado", "data"=>$data["items"], "order"=>$data["order"]]);
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

        $data = $this->ReturnData($request->fk_order);

        return response()->json(['status'=>true, 'message'=>"Orden Actualizada", "data"=>$data["items"], "order"=>$data["order"]]);
    }

    public function GetPDF($total, $broker, $pay, $iva, $payt, $idOrder)
    {
        $client = DB::table('users')->select(DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(users.firstname, "")," ",IFNULL(users.lastname, "")) AS cname'),'destiny','quote_date')
            ->join('Orders',"users.id","=","fk_user")
            ->where('Orders.id',$idOrder)
            ->whereNull('Orders.deleted_at')->first();
        $pdf = new PDFQuote();
        $pdf->PrintPDF($client->cname,$client->destiny,$client->quote_date,$total, $broker, $pay, $iva, $payt);
        $pdf->Output('D',"Cotización.pdf");
        // $pdf->Output('F',public_path("img/")."Cotizacion.pdf");
        return;
    }

    public function GetInfoOrder($id)
    {
        $order = Order::where('id',$id)->first();
        // dd($profile);
        return response()->json(['status'=>true, "data"=>$order]);
    }

    public function CreateOrder(Request $request)
    {
        $order = Order::where('id',$request->idupdate)
            ->update(['quote'=>1, 'order_number'=>$request->order_number, 'address'=>$request->address]);
        return response()->json(["status"=>true, "message"=>"Orden creada"]);
        // dd("entre");
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return response()->json(['status'=>true, "message"=>"Cotización eliminada"]);
    }
}
