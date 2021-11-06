<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Permission;
use App\MonthlyComission;
use App\Nuc;
use App\Status;
use DB;

class MonthComissionController extends Controller
{
    public function index(){
        $profile = User::findProfile();
        $users = DB::table('users')->select('id',DB::raw('CONCAT(name," ",firstname," ",lastname) AS name'))->whereNull('deleted_at')->get();
        $perm = Permission::permView($profile,21);
        $perm_btn =Permission::permBtns($profile,21);
        // dd($clients);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('funds.monthcomission.monthcomission', compact('users','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $clients = DB::table('Nuc')->select("Nuc.id as idNuc","nuc", DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS client_name'))
        ->join('Client',"Client.id","=","fk_client")->where('fk_agent',$id)
        ->get();
        return response()->json(['status'=>true, "data"=>$clients]);
    }
    public function GetInfoMonth($id,$month,$year)
    {
        $movimientos = DB::table('Month_fund')->select("*")->where('fk_nuc',$id)->whereMonth('apply_date',$month)->whereYear('apply_date',$year)->whereNull('deleted_at')->get();
        // dd($movimientos);
        return response()->json(['status'=>true, "data"=>$movimientos]);
    }
    public function GetInfoLast($id)
    {
        $movements = DB::table('Month_fund')->select('*')->where('fk_nuc',$id)->orderByRaw('id DESC')->first();
        // dd($movements);
        return response()->json(['status'=>true, "data"=>$movements]);
    }

    public function ExportPDF($id){
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML('
        <html>
        <head>
            <style>
                @page {
                    margin: 0cm 0cm;
                    font-family: Arial;
                }

                body {
                    margin: 3cm 2cm 2cm;
                }

                header {
                    position: fixed;
                    top: 0cm;
                    left: 0cm;
                    right: 0cm;
                    height: 2cm;
                    background-color: #2a0927;
                    color: white;
                    text-align: center;
                    line-height: 30px;
                }

                footer {
                    position: fixed;
                    bottom: 0cm;
                    left: 0cm;
                    right: 0cm;
                    height: 2cm;
                    background-color: #2a0927;
                    color: white;
                    text-align: center;
                    line-height: 35px;
                }
            </style>
        </head>
        <body>
        <header>
            <h1>Styde.net</h1>
        </header>

        <main>
            <h1>Contenido</h1>
        </main>

        <footer>
            <h1>www.styde.net</h1>
        </footer>
        </body>
        </html>
        ');
        return $pdf->download('mi-archivo.pdf');
    }
}
