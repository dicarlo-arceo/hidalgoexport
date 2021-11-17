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

    // public function GetInfoMonth($id,$month,$year)
    // {
    //     $movimientos = DB::table('Month_fund')->select("*")->where('fk_nuc',$id)->whereMonth('apply_date',$month)->whereYear('apply_date',$year)->whereNull('deleted_at')->get();
    //     // dd($movimientos);
    //     return response()->json(['status'=>true, "data"=>$movimientos]);
    // }

    // public function GetInfoLast($id,$month,$year)
    // {
    //     $fecha = $year.'/'.$month.'/01';
    //     // dd($fecha);
    //     $movements = DB::table('Month_fund')->select('*')->where('fk_nuc',$id)->where('apply_date','<',$fecha)
    //     ->orderByRaw('id DESC')->first();
    //     // dd($movements);
    //     return response()->json(['status'=>true, "data"=>$movements]);
    // }

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
                    max-height: 500px;
                }

                header {
                    position: fixed;
                    top: 0cm;
                    left: 0cm;
                    right: 0cm;
                    height: 2cm;
                    background-color: #106a6a;
                    color: white;
                    text-align: center;
                    line-height: 30px;
                }

                table{
                    align: center;
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
            <h1>Agosto(Septiembre)</h1>
        </header>

        <main>
            <h1>Noyola</h1>
            <table class="table table-striped table-hover text-center" id="tbProf">

                <tbody>
                    <tr>
                        <td>Cliente 1</td>
                    </tr>
                    <tr>
                        <td>Cliente 2</td>
                    </tr>
                    <tr>
                        <td>Cliente 3</td>
                    </tr>
                    <tr>
                        <td>Cliente 4</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <br>
            <table class="table table-striped table-hover text-center" id="tbProf">

                <tbody>
                    <tr>
                        <td>Monto bruto</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$2,000.00</td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$320.00</td>
                    </tr>
                    <tr>
                        <td>RET ISR</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$200.00</td>
                    </tr>
                    <tr>
                        <td>RET IVA</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$213.33</td>
                    </tr>
                    <tr>
                        <td>Monto Neto</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$1,906.67</td>
                    </tr>
                </tbody>
            </table>
        </main>
        </body>
        </html>
        ');
        return $pdf->download('mi-archivo.pdf');
    }

    public function GetInfoComition(Request $request)
    {
        // dd($request->all());
        $b_amount=0;//Saldo cierre de mes
        $dll_conv=0;//conversion a usd
        $usd_invest=0;//para cada 5,00 usd sobre el monto invertido(esto multiplicar x10)
        $usd_invest1=0;//para cada 5,00 usd sobre el monto invertido(esto multiplicar x10)

        $gross_amount=0;//Monto bruto = usd_invest * $request->TC

        $iva_amount=0;//iva del monto bruto
        $ret_isr=0; //retencion del isr 10% monto bruto
        $ret_iva=0;//retencion de iva son 2 3ras partes del iva de montro bruto 2(IVA)/3
        $n_amount=0;//monto neto

        // condicion para determinar fecha de corte
        $nuc = DB::table('Nuc')->select("cut_date","currency")->where('id',$request->id)->first();
        // dd($request->year."-".(intval($request->month)-1)."-15");
        // dd($cut_date->cut_date);
        if(intval($nuc->cut_date) > 15)
        {
            // dd("entre a mayor que 15");
            // consultas para corte al día 30
            $data = DB::table('Month_fund')->select("*")
            ->join('Nuc','Nuc.id','=','Month_fund.fk_nuc')
            ->where('fk_nuc',$request->id)->whereMonth('apply_date',$request->month)
            ->whereYear('apply_date',$request->year)->whereNull('Month_fund.deleted_at')->get();

            if($data->isEmpty())
            {
                $fecha = $request->year.'/'.$request->month.'/01';
                $data = DB::table('Month_fund')->select('*')
                ->join('Nuc','Nuc.id','=','fk_nuc')
                ->where('fk_nuc',$request->id)->where('apply_date','<',$fecha)
                ->whereNull('Month_fund.deleted_at')
                ->orderByRaw('Month_fund.id DESC')->first();
                $b_amount = $data->new_balance;
            }
            else
            {
                // cálculo para mes con movimientos corte día 30
                $balance = 0;
                // dd($data);
                if(count($data) == 1)
                {
                    $data = $data[0];
                    $day = explode("-", $data->apply_date);
                    $day = intval($day[2]);
                    if($day == 1)
                    {
                        $b_amount = $data->new_balance;
                    }
                    else if($day == 30 || $day == 31)
                    {
                        $b_amount = (29*$data->prev_balance + $data->new_balance)/30;
                    }
                    else
                    {
                        $b_amount = ($day*$data->prev_balance + (30-$day)*$data->new_balance)/30;
                    }
                    // dd($b_amount);
                }
                else
                {
                    $movs = array();
                    foreach ($data as $movimiento)
                    {
                        $day = explode("-", $movimiento->apply_date);
                        $day = intval($day[2]);
                        $mov = array($day, $movimiento->prev_balance, $movimiento->new_balance);
                        array_push($movs,$mov);
                    }
                    // dd($movs);
                    for($x = 0; $x <= count($movs); $x++)
                    {
                        if($x == 0)
                        {
                            $b_amount = $movs[$x][0]*$movs[$x][1];
                        }
                        else if($x == count($movs))
                        {
                            $b_amount += (30-$movs[$x-1][0])*$movs[$x-1][2];
                        }
                        else
                        {
                            $b_amount += ($movs[$x][0]-$movs[$x-1][0])*$movs[$x][1];
                        }
                    }
                    $b_amount /= 30;
                    // dd($b_amount);
                }
            }
        }
        else
        {
            // dd("entre a menor que 15");
            $data = DB::table('Month_fund')->select("*")
            ->join('Nuc','Nuc.id','=','Month_fund.fk_nuc')
            ->where('fk_nuc',$request->id)
            ->whereBetween('apply_date', [$request->year."-".(intval($request->month)-1)."-15", $request->year."-".(intval($request->month))."-15"])
            ->whereNull('Month_fund.deleted_at')->get();
            if($data->isEmpty())
            {
                $fecha = $request->year.'/'.(intval($request->month)-1).'/15';
                $data = DB::table('Month_fund')->select('*')
                ->join('Nuc','Nuc.id','=','fk_nuc')
                ->where('fk_nuc',$request->id)->where('apply_date','<',$fecha)
                ->whereNull('Month_fund.deleted_at')
                ->orderByRaw('Month_fund.id DESC')->first();
                $b_amount = $data->new_balance;
            }
            else
            {
                // cálculo para mes con movimientos corte día 15
                $balance = 0;
                // dd(count($data));
                if(count($data) == 1)
                {
                    $data = $data[0];
                    $day = explode("-", $data->apply_date);
                    $month = intval($day[1]);
                    $day = intval($day[2]);
                    if($day == 15)
                    {
                        if($month == ($request->month - 1))
                        {
                            $b_amount = $data->new_balance;
                        }
                        else if($month == $request->month)
                        {
                            $b_amount = (29*$data->prev_balance + $data->new_balance)/30;
                        }
                    }
                    else if($day > 15)
                    {
                        $b_amount = (($day-15)*$data->prev_balance + (45-$day)*$data->new_balance)/30;

                    }
                    else if($day < 15)
                    {
                        $b_amount = ((15+$day)*$data->prev_balance + (15-$day)*$data->new_balance)/30;
                    }
                    // dd($b_amount);
                }
                else
                {
                    $movs = array();
                    foreach ($data as $movimiento)// los días menores a 15 se les debe sumar 30
                    {
                        $day = explode("-", $movimiento->apply_date);
                        $month = intval($day[1]);
                        $day = intval($day[2]);
                        if($day > 15)
                        {
                            $mov = array($day-14, $movimiento->prev_balance, $movimiento->new_balance);
                        }
                        else
                        {
                            $mov = array($day+16, $movimiento->prev_balance, $movimiento->new_balance);
                        }
                        array_push($movs,$mov);
                    }
                    // dd($movs);
                    for($x = 0; $x <= count($movs); $x++)
                    {
                        if($x == 0)// calcular el numero de dias restandole 15
                        {
                            $b_amount = $movs[$x][0]*$movs[$x][1];
                        }
                        else if($x == count($movs))// calcular a 45 en lugar de 30
                        {
                            $b_amount += (30-$movs[$x-1][0])*$movs[$x-1][2];
                        }
                        else
                        {
                            $b_amount += ($movs[$x][0]-$movs[$x-1][0])*$movs[$x][1];
                        }
                    }
                    $b_amount /= 30;
                    // dd($b_amount);
                }
            }
        }


        // $data = DB::table('Month_fund')->select('*')
        // ->join('Nuc','Nuc.id','=','fk_nuc')
        // ->where('Month_fund.fk_nuc',$request->id)
        // ->orderByRaw('Month_fund.id DESC')->first();

        if($nuc->currency == "MXN")
        {
            $dll_conv = $b_amount / $request->TC; //si es en pesos, ponemos valor en usd

        }else{
            $dll_conv = $b_amount; //si es en dolares, se queda igual

        }

        $usd_invest = $dll_conv/5000; //por cada 5000 sobre el monto invertido
        $usd_invest1 = $usd_invest*10; //se multiplica por 10 el resultado obtenido

        $gross_amount = $usd_invest1 * $request->TC; //monto bruto

        $iva_amount = $gross_amount * .16; // iva del monto bruto

        $ret_isr = $gross_amount *.10; //isr del monto bruto

        $ret_iva = 2*$iva_amount; //retencion de iva
        $ret_iva = $ret_iva/3; //retencion del iva

        $n_amount= ($gross_amount + $iva_amount) - ($ret_isr + $ret_iva); //Monto neto

        // dd(number_format($iva_amount,2,'.',''));
        return response()->json(['status'=>true, "b_amount"=>number_format($b_amount,2,'.',','),'dll_conv'=>number_format($dll_conv,2,'.',','),'usd_invest'=>number_format($usd_invest1,2,'.',','),
        'gross_amount'=>number_format($gross_amount,2,'.',','), 'iva_amount'=>number_format($iva_amount,2,'.',','), 'ret_isr'=>number_format($ret_isr,2,'.',','), 'ret_iva'=>number_format($ret_iva,2,'.',','), 'n_amount'=>number_format($n_amount,2,'.',',')]);
    }
}
