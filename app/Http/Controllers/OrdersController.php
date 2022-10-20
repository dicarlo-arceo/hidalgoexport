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
        $title = "Órdenes";
        $flagClosed = 0;
        // dd($profile);
        if($profile != 61)
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where('stat_open','=',0)
            ->whereNull('Orders.deleted_at')->get();
            // dd($orders);
        }
        else
        {
            $orders = DB::table('Orders')->select('Orders.id','order_number','Projects.name AS project', DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
            ->join('users',"fk_user","=","users.id")
            ->join('Projects',"fk_project","=","Projects.id")
            ->where("fk_user","=",User::user_id())
            ->where('stat_open','=',0)
            ->whereNull('Orders.deleted_at')->get();
        }
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('processes.order.orders', compact('orders','perm_btn','profile','projects','cmbStatus','title','flagClosed'));
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
        $flag = 0;
        $item = Item::where('id',$request->id)
        ->update(['tr'=>$request->tr]);

        $items = $this->GetItemsBack($request->fk_order,$request->flagTR);
        if(count($items) == 0)
        {
            // dd("entre");
            $flag = 1;
            $items = DB::table('Orders')->select("*")
                ->where('id',$request->fk_order)
                ->whereNull('deleted_at')->get();
        }
        // dd($items);
        return response()->json(['status'=>true, 'message'=>"Orden Actualizada", "data"=>$items,"flag"=>$flag]);
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
        $order = DB::table('Orders')->select("Projects.name as projectName","address",'cellphone',"order_number",DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
                ->join('Projects',"Projects.id","=","fk_project")
                ->join('users',"users.id","=","fk_user")
                ->where('Orders.id',"=",$id)->first();
        $pdf = new PDF();
        $pdf->PrintChapter($order,$cellar,$comition,$dlls,$date,$pkgs,number_format(floatval(preg_replace('/[^\d\.]+/', '', $cellar)) * floatval($dlls),2,".",","),number_format(floatval(preg_replace('/[^\d\.]+/', '', $comition)) * floatval($dlls),2,".",","));
        $pdf->Output('D',"Orden_".$order->order_number.".pdf");
        return;
    }
    public function ItemsPDF($order,$tr,$cellar,$comition,$mxn_total,$iva,$mxn_invoice,$usd_total,$broker,$flaginvoice)
    {
        $items = $this->GetItemsBack($order,$tr);
        $ord = DB::table('Orders')->select("*","Projects.name as projectName",DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
                    ->join('Projects',"Projects.id","=","fk_project")
                    ->join('users',"users.id","=","fk_user")
                    ->where('Orders.id',"=",$order)->first();

        $pdf = new PDFItems();
        $pdf->PrintPDF($items,$ord,$cellar,$comition,$mxn_total,$iva,$mxn_invoice,$usd_total,$broker,$ord->expenses,$flaginvoice,$ord->projectName." / ");
        $orderName = Order::select("order_number")->where('id',$order)->first();
        // dd($orderName);
        $pdf->Output('D',"Items_de_orden_".$orderName->order_number.".pdf");
        return;
    }
    public function GetinfoStatusOrder($id)
    {
        $item = DB::table('Items')->select("Status.id","name")
            ->join('Status',"Status.id","=","fk_status")
            ->groupBy('fk_status')
            ->where('fk_order',"=",$id)
            ->whereNull('Items.deleted_at')->get();
        // dd($item);
        return response()->json(['status'=>true, "data"=>$item]);
    }
    public function updateStatusOrder(Request $request)
    {
        $flag = 0;
        foreach ($request->ids as $item)
        {
            $itm = Item::where('id',$item)->first();
            // dd($request->statusNew,$itm->back_order,$itm->fk_status);
            if($request->statusNew == "8" && $itm->back_order != 0 && $itm->fk_status != 8)
            {
                $newitem = new Item;
                $newitem->fk_order = $itm->fk_order;
                $newitem->store = $itm->store;
                $newitem->item_number = $itm->item_number;
                $newitem->description = $itm->description;
                $newitem->back_order = $itm->back_order;
                $newitem->existence = 0;
                $newitem->fk_status = 6;
                $newitem->net_price = 0;
                $newitem->total_price = 0;
                $newitem->save();
            }

            if($request->trStatusAll == "") $itm = Item::where('id',$item)->update(['fk_status'=>$request->statusNew]);
            else if($request->statusNew == null) $itm = Item::where('id',$item)->update(['tr'=>$request->trStatusAll]);
            else $itm = Item::where('id',$item)->update(['fk_status'=>$request->statusNew,'tr'=>$request->trStatusAll]);
        }

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
    public function GetinfoTROrders($id, Request $request)
    {
        $trs = array();
        $adrss = array();
        foreach ($request->ids as $id)
        {
            $items = DB::table('Items')->select("tr")
            ->where('fk_order',$id)
            ->where('fk_status',"=",8)
            ->whereNull('Items.deleted_at')
            ->groupBy('tr')->get();

            $address = DB::table('Orders')->select("id","address")
            ->where('id',$id)->first();
            $auxaddr = array('id' => $address->id, 'address' => $address->address);
            array_push($adrss, $auxaddr);
            // array_splice($adrss,$address->id,0,$address->address);
            // $adrss += $auxaddr;

            foreach($items as $item)
            {
                array_push($trs,$item->tr);
            }
        }
        $trs = array_unique($trs);
        $adrss = $this->unique_multidim_array($adrss,'address');
        sort($trs);
        return response()->json(['status'=>true, "data"=>$trs, "address"=>$adrss]);
    }
    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
    public function GetPDFCobroTodos($flag,$date,$pkgs,$tr,$address,$ids)
    {
        $totalCellar = 0;
        $totalComition = 0;
        $mxnCellar = 0;
        $mxnComition = 0;
        $totalmxnCellar = 0;
        $totalmxnComition = 0;
        // dd($flag);
        if($flag == "0")
        {
            $ids = explode("-", $ids);
            // dd($ids);
            $cellar = 0;
            $prices = array();
            foreach($ids as $id)
            {
                $cellar = 0;
                $items = DB::table('Items')->select("total_price")
                ->join('Orders',"Orders.id","=","fk_order")
                ->join('Status',"Status.id","=","fk_status")
                ->where('fk_order',$id)
                ->where('Status.id',"=",8)
                ->where('tr',"=",$tr)
                ->whereNull('Items.deleted_at')->get();

                $order = DB::table('Orders')->select("exc_rate","percentage","expenses","currency","broker","roundout")
                    ->where('Orders.id',"=",$id)->first();

                // dd($order);
                if($items->count() != 0)
                {
                    foreach($items as $item)
                    {
                        $cellar += floatval($item->total_price);
                    }


                    $comition = $cellar * floatval($order->percentage)/100;
                    if($comition > 0 && $comition < 100 && $order->roundout == 1) $comition = 100;
                    if(intval($order->currency) == 1) $comition += floatval($order->expenses);
                    $comition += floatval($order->broker);
                    $mxnCellar = $cellar * floatval($order->exc_rate);
                    $mxnComition = $comition * floatval($order->exc_rate);
                    $auxarray = array('cellar' => $cellar, 'comition' => $comition, 'mxnCellar' => $mxnCellar, 'mxnComition' => $mxnComition);
                    array_push($prices,$auxarray);
                    // dd(number_format($cellar, 2, ".", ","),number_format(floatval($order->exc_rate), 2, ".", ","));
                }
            }

            foreach($prices as $price)
            {
                // dd($price);
                $totalCellar += $price['cellar'];
                $totalComition += $price['comition'];
                $totalmxnCellar += $price['mxnCellar'];
                $totalmxnComition += $price['mxnComition'];
            }
            // dd(number_format(floatval($totalCellar),2,".",","),number_format(floatval($totalComition),2,".",","));
            $order = DB::table('Orders')->select("Projects.name as projectName","address",'cellphone',"order_number","exc_rate",DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
                    ->join('Projects',"Projects.id","=","fk_project")
                    ->join('users',"users.id","=","fk_user")
                    ->where('Orders.id',"=",$address)->first();
            $pdf = new PDF();
            $pdf->PrintChapter($order,"$".number_format(floatval($totalCellar),2,".",","),"$".number_format(floatval($totalComition),2,".",","),number_format(floatval($order->exc_rate),2,".",","),$date,$pkgs,number_format(floatval($totalmxnCellar),2,".",","),number_format(floatval($totalmxnComition),2,".",","),$tr);

        }
        else
        {
            $totalCellar = "1";
            $totalComition = "1";
            $order = DB::table('Orders')->select("Projects.name as projectName","address",'cellphone',"order_number","exc_rate",DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
                    ->join('Projects',"Projects.id","=","fk_project")
                    ->join('users',"users.id","=","fk_user")
                    ->where('Orders.id',"=",$address)->first();
            $pdf = new PDF();
            $pdf->PrintChapter($order,$totalCellar,$totalComition,number_format(floatval($order->exc_rate),2,".",","),$date,$pkgs,$totalmxnCellar,$totalmxnComition,$tr);
        }
        // dd($totalCellar,$totalComition);

        $pdf->Output('D',"HojaDeCobro.pdf");
    }
    public function updateBOAll(Request $request)
    {
        $flag = 0;
        foreach ($request->ids as $item)
        {
            $itm = Item::where('id',$item)->first();
            if($itm->back_order != 0)
            {
                $itm = Item::where('id',$item)->update(['back_order'=>0,'existence'=>$itm->existence + $itm->back_order,'total_price' => ($itm->existence + $itm->back_order)*$itm->net_price]);
            }
        }

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
    public function GetPDFItemsTodos($flag,$tr,$ids,$flaginvoice,$status)
    {
        $totalCellar = 0;
        $totalComition = 0;
        $totalBroker = 0;
        $totalExpenses = 0;
        $totalUsd_total = 0;
        $totalMxn_total = 0;
        $totalIva = 0;
        $totalMxn_invoice = 0;
        // dd($flag);
        $ids = explode("-", $ids);

        if(intval($tr) != 0)
        {
            if(intval($status) == 0)
            {
                $itm = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
                    ->join('Orders',"Orders.id","=","fk_order")
                    ->join('Status',"Status.id","=","fk_status")
                    ->whereIn('fk_order',$ids)
                    ->where('tr',"=",$tr)
                    ->whereNull('Items.deleted_at')->get();
            }
            else
            {
                $itm = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
                    ->join('Orders',"Orders.id","=","fk_order")
                    ->join('Status',"Status.id","=","fk_status")
                    ->whereIn('fk_order',$ids)
                    ->where('Status.id',"=",intval($status))
                    ->where('tr',"=",$tr)
                    ->whereNull('Items.deleted_at')->get();
            }
        }
        else
        {
            if(intval($status) == 0)
            {
                $itm = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
                    ->join('Orders',"Orders.id","=","fk_order")
                    ->join('Status',"Status.id","=","fk_status")
                    ->whereIn('fk_order',$ids)
                    ->whereNull('Items.deleted_at')->get();
            }
            else
            {
                $itm = DB::table('Items')->select("*","Items.id as id",'Status.id as statId')
                    ->join('Orders',"Orders.id","=","fk_order")
                    ->join('Status',"Status.id","=","fk_status")
                    ->whereIn('fk_order',$ids)
                    ->where('Status.id',"=",intval($status))
                    ->whereNull('Items.deleted_at')->get();
            }
        }

        if($flag == "0")
        {
            // dd($ids);
            $cellar = 0;
            $prices = array();
            $cont = 0;

            foreach($ids as $id)
            {
                $cellar = 0;

                if(intval($tr) != 0)
                {
                    if(intval($status) == 0)
                    {
                        $items = DB::table('Items')->select("total_price")
                            ->join('Orders',"Orders.id","=","fk_order")
                            ->join('Status',"Status.id","=","fk_status")
                            ->where('fk_order',$id)
                            ->where('tr',"=",$tr)
                            ->whereNull('Items.deleted_at')->get();
                    }
                    else
                    {
                        $items = DB::table('Items')->select("total_price")
                            ->join('Orders',"Orders.id","=","fk_order")
                            ->join('Status',"Status.id","=","fk_status")
                            ->where('fk_order',$id)
                            ->where('Status.id',"=",intval($status))
                            ->where('tr',"=",$tr)
                            ->whereNull('Items.deleted_at')->get();
                    }
                }
                else
                {
                    if(intval($status) == 0)
                    {
                        $items = DB::table('Items')->select("total_price")
                            ->join('Orders',"Orders.id","=","fk_order")
                            ->join('Status',"Status.id","=","fk_status")
                            ->where('fk_order',$id)
                            ->whereNull('Items.deleted_at')->get();
                    }
                    else
                    {
                        $items = DB::table('Items')->select("total_price")
                            ->join('Orders',"Orders.id","=","fk_order")
                            ->join('Status',"Status.id","=","fk_status")
                            ->where('fk_order',$id)
                            ->where('Status.id',"=",intval($status))
                            ->whereNull('Items.deleted_at')->get();
                    }
                }

                $order = DB::table('Orders')->select("exc_rate","percentage","expenses","currency","broker","roundout")
                ->where('Orders.id',"=",$id)->first();
                if($items->count() != 0)
                {
                    foreach($items as $item)
                    {
                        $cellar += floatval($item->total_price);
                    }

                    $usd_total = 0;
                    $mxn_total = 0;

                    $comition = $cellar * floatval($order->percentage)/100;

                    if($comition > 0 && $comition < 100 && $order->roundout == 1) $comition = 100;

                    if(intval($order->currency) == 1) $usd_total += floatval($order->expenses);
                    else $mxn_total += floatval($order->expenses);

                    $usd_total += $comition + floatval($order->broker);
                    $mxn_total += $usd_total * floatval($order->exc_rate);
                    $iva = $mxn_total * 0.16;
                    $mxn_invoice = $mxn_total + $iva;

                    $auxarray = array('cellar' => $cellar, 'comition' => $comition, 'broker' => $order->broker, 'expenses' => $order->expenses, 'usd_total' => $usd_total, 'mxn_total' => $mxn_total, 'iva' => $iva, 'mxn_invoice' => $mxn_invoice);
                    array_push($prices,$auxarray);
                    $cont ++;
                }
            }

            foreach($prices as $price)
            {
                // dd($price);
                $totalCellar += $price['cellar'];
                $totalComition += $price['comition'];
                $totalBroker += $price['broker'];
                $totalExpenses += $price['expenses'];
                $totalUsd_total += $price['usd_total'];
                $totalMxn_total += $price['mxn_total'];
                $totalIva += $price['iva'];
                $totalMxn_invoice += $price['mxn_invoice'];
            }
            // dd(number_format(floatval($totalCellar),2,".",","),number_format(floatval($totalComition),2,".",","));
            $ord = DB::table('Orders')->select("*","Projects.name as projectName",DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
                    ->join('Projects',"Projects.id","=","fk_project")
                    ->join('users',"users.id","=","fk_user")
                    ->where('Orders.id',"=",$ids[0])->first();

            $projNames = DB::table('Orders')->select("Projects.name as projectName")
                ->join('Projects',"Projects.id","=","fk_project")
                ->groupBy('projectName')
                ->whereIn('Orders.id',$ids)->get();

            $projName = "";
            foreach($projNames as $name)
            {
                $projName = $projName.$name->projectName." / ";
            }

            $pdf = new PDFItems();
            $pdf->PrintPDF($itm,$ord,
            "$".number_format(floatval($totalCellar),2,".",","),
            "$".number_format(floatval($totalComition),2,".",","),
            "$".number_format(floatval($totalMxn_total),2,".",","),
            "$".number_format(floatval($totalIva),2,".",","),
            "$".number_format(floatval($totalMxn_invoice),2,".",","),
            "$".number_format(floatval($totalUsd_total),2,".",","),
            number_format(floatval($totalBroker),2,".",","),
            number_format(floatval($totalExpenses),2,".",","),$flaginvoice,$projName);

        }
        else
        {
            $totalCellar = "1";
            $totalComition = "1";
            $ord = DB::table('Orders')->select("*","Projects.name as projectName",DB::raw('CONCAT(IFNULL(users.name, "")," ",IFNULL(firstname, "")," ",IFNULL(lastname, "")) AS name'))
                    ->join('Projects',"Projects.id","=","fk_project")
                    ->join('users',"users.id","=","fk_user")
                    ->where('Orders.id',"=",$ids[0])->first();

            $projNames = DB::table('Orders')->select("Projects.name as projectName")
                ->join('Projects',"Projects.id","=","fk_project")
                ->groupBy('projectName')
                ->whereIn('Orders.id',$ids)->get();

            $projName = "";
            foreach($projNames as $name)
            {
                $projName = $projName.$name->projectName." / ";
            }

            $pdf = new PDFItems();
            $pdf->PrintPDF($itm,$ord,$totalCellar,$totalComition,
            "$".number_format(floatval($totalMxn_total),2,".",","),
            "$".number_format(floatval($totalIva),2,".",","),
            "$".number_format(floatval($totalMxn_invoice),2,".",","),
            "$".number_format(floatval($totalUsd_total),2,".",","),
            number_format(floatval($totalBroker),2,".",","),
            number_format(floatval($totalExpenses),2,".",","),$flaginvoice,$projName);
        }


        // dd($orderName);
        $pdf->Output('D',"Hoja_de_Items.pdf");
        return;
    }

    public function CloseOrders(Request $request)
    {
        foreach($request->ids as $id)
        {
            $order = Order::where('id',$id)
                ->update(['stat_open'=>1]);
        }
        return response()->json(['status'=>true, "message"=>"Ordenes actualizadas"]);
    }

    public function OpenOrders(Request $request)
    {
        foreach($request->ids as $id)
        {
            $order = Order::where('id',$id)
                ->update(['stat_open'=>0]);
        }
        return response()->json(['status'=>true, "message"=>"Ordenes actualizadas"]);
    }
    public function AssignReceipt(Request $request)
    {
        $ids = explode("-", $request->ids);
        $fk_client = Order::select('fk_user')->where('id','=',$ids[0])->first();

        $orders = "";
        $aux = 0;
        foreach($ids as $id)
        {
            $order = Order::select('order_number')->where('id','=',$id)->first();
            $orders = $orders.strval($order->order_number);
            if(count($ids)-1 != $aux) $orders = $orders."_";
            $aux++;
        }

        $receipt = new Receipt_assigns;
        $receipt->idsOrd = $request->ids;
        $receipt->orders = $orders;
        $receipt->tr = $request->tr;
        $receipt->fk_client = $fk_client->fk_user;

        if($request->hasFile("receipt")){

            $imagen = $request->file("receipt");
            $nombreimagen = "Receipt_Orders_".$orders."_TR_".Str::slug($request->tr).".".$imagen->guessExtension();
            $ruta = public_path("img/receipts/");

            copy($imagen->getRealPath(),$ruta.$nombreimagen);

            $receipt->receipt = $nombreimagen;

        }

        $receipt->save();

        return response()->json(['status'=>true, "message"=>"Recibo Registrado"]);
    }
}
