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

class FstMonthComissionController extends Controller
{
    public function index(){
        $profile = User::findProfile();
        $users = DB::table('users')->select('users.id',DB::raw('CONCAT(users.name," ",users.firstname," ",users.lastname) AS name'))
            ->join('Client',"fk_agent","=","users.id")
            ->join('Nuc',"fk_client","=","Client.id")
            ->where("month_flag","<","7")
            ->groupBy("name")
            ->whereNull('users.deleted_at')->get();
        $perm = Permission::permView($profile,22);
        $perm_btn =Permission::permBtns($profile,22);
        // dd($clients);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('funds.fstmonthcomission.fstmonthcomission', compact('users','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $clients = DB::table('Nuc')->select("Nuc.id as idNuc","nuc", DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS client_name'))
        ->join('Client',"Client.id","=","fk_client")->where('fk_agent',$id)->where("month_flag","<","7")
        ->get();
        $regime = DB::table('users')->select('regime')->where('id',$id)->first();
        return response()->json(['status'=>true, "regime"=>$regime->regime, "data"=>$clients]);
    }
    public function ExportPDF($id,$month,$year,$TC,$regime){


        // dd($id,$month,$year,$TC);
        $b_amount = 0;
        $IVA = 0;
        $ret_isr = 0;
        $ret_iva = 0;
        $n_amount = 0;
        // setlocale(LC_TIME, 'es_ES.UTF-8');
        // $monthName = date('F', mktime(0, 0, 0, $month, 10));
        $months = array (1=>'Enero',2=>'Febrer',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
        $clients = DB::table('Client')->select(DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS name'),"fk_agent")
        ->join('Nuc',"fk_client","=","Client.id")
        ->where("month_flag","<","7")
        ->groupBy("name")
        ->where('Nuc.id',$id)->get();
        $clientNames = "";
        $userName = DB::table('users')->select(DB::raw('CONCAT(users.name," ",users.firstname," ",users.lastname) AS name'))
            ->where('users.id',$clients[0]->fk_agent)->whereNull('users.deleted_at')->first();

        $value5 = $this->calculo($id,$month,$year,$TC,10,$regime);
        $value1 = $this->calculo($id,$month,$year,$TC,35,$regime);
        // dd($value5,$value1);
        $b_amount += $value5["gross_amount"]*5 + $value1["gross_amount"];
        $IVA += $value5["iva_amount"]*5 + $value1["iva_amount"];
        $ret_isr += $value5["ret_isr"]*5 + $value1["ret_isr"];
        $ret_iva += $value5["ret_iva"]*5 + $value1["ret_iva"];
        $n_amount += $value5["n_amount"]*5 + $value1["n_amount"];
            // dd($clientNames);
        // dd($b_amount,$IVA,$ret_isr,$ret_iva,$n_amount);

        foreach ($clients as $client)
        {
            $clientNames = $clientNames."<tr><td>".$client->name."</tr></td>";
            // dd($clientNames);
        }
        // dd($clientNames);
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
            <h1>'.$months[intval($month)]." ".$year.'</h1>
        </header>

        <main>
            <h1>'.$userName->name.'</h1>
            <br>
            <h2>Clientes</h2>
            <table class="table table-striped table-hover text-center" id="tbProf">

                <tbody>
                    '.$clientNames.'
                </tbody>
            </table>
            <br>
            <h2>Totales</h2>
            <table class="table table-striped table-hover text-center" id="tbProf">

                <tbody>
                    <tr>
                        <td>Monto bruto</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($b_amount,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($IVA,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>RET ISR</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($ret_isr,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>RET IVA</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($ret_iva,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>Monto Neto</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($n_amount,2,'.',',').'</td>
                    </tr>
                </tbody>
            </table>
        </main>
        </body>
        </html>
        ');
        return $pdf->download($months[intval($month)]."_".$year."_".$userName->name.'.pdf');
    }

    public function ExportPDFAll($id,$month,$year,$TC,$regime){

        // dd($id,$month,$year,$TC);
        $b_amount = 0;
        $IVA = 0;
        $ret_isr = 0;
        $ret_iva = 0;
        $n_amount = 0;
        // setlocale(LC_TIME, 'es_ES.UTF-8');
        // $monthName = date('F', mktime(0, 0, 0, $month, 10));
        $months = array (1=>'Enero',2=>'Febrer',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
        $userName = DB::table('users')->select(DB::raw('CONCAT(users.name," ",users.firstname," ",users.lastname) AS name'))
            ->where('users.id',$id)->whereNull('users.deleted_at')->first();
        $nucs = DB::table('Nuc')->select("Nuc.id as id")
            ->join('Client',"Client.id","=","fk_client")
            ->where("month_flag","<","7")
            ->where('fk_agent',$id)
            ->get();
        $clients = DB::table('Client')->select(DB::raw('CONCAT(Client.name," ",Client.firstname," ",Client.lastname) AS name'))
            ->join('Nuc',"fk_client","=","Client.id")
            ->where("month_flag","<","7")
            ->groupBy("name")
            ->where('fk_agent',$id)->get();
        $clientNames = "";
        foreach ($nucs as $nuc)
        {
            $value = $this->calculo($nuc->id,$month,$year,$TC,10,$regime);
            // dd($value);
            $b_amount += $value["gross_amount"];
            $IVA += $value["iva_amount"];
            $ret_isr += $value["ret_isr"];
            $ret_iva += $value["ret_iva"];
            $n_amount += $value["n_amount"];
            // dd($clientNames);
        }
        // dd($b_amount,$IVA,$ret_isr,$ret_iva,$n_amount);
        foreach ($clients as $client)
        {
            $clientNames = $clientNames."<tr><td>".$client->name."</tr></td>";
            // dd($clientNames);
        }
        // dd($clientNames);
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
            <h1>'.$months[intval($month)]." ".$year.'</h1>
        </header>

        <main>
            <h1>'.$userName->name.'</h1>
            <br>
            <h2>Clientes</h2>
            <table class="table table-striped table-hover text-center" id="tbProf">

                <tbody>
                    '.$clientNames.'
                </tbody>
            </table>
            <br>
            <h2>Totales</h2>
            <table class="table table-striped table-hover text-center" id="tbProf">

                <tbody>
                    <tr>
                        <td>Monto bruto</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($b_amount,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($IVA,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>RET ISR</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($ret_isr,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>RET IVA</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($ret_iva,2,'.',',').'</td>
                    </tr>
                    <tr>
                        <td>Monto Neto</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;$'.number_format($n_amount,2,'.',',').'</td>
                    </tr>
                </tbody>
            </table>
        </main>
        </body>
        </html>
        ');
        return $pdf->download($months[intval($month)]."_".$year."_".$userName->name.'.pdf');
    }


    public function GetInfoComition(Request $request)
    {
        $values = $this->calculo($request->id,$request->month,$request->year,$request->TC,10,$request->regime);
        // dd($request->id);
        // dd(number_format($iva_amount,2,'.',''));
        return response()->json(['status'=>true, "b_amount"=>number_format($values["b_amount"],2,'.',','),'dll_conv'=>number_format($values["dll_conv"],2,'.',','),'usd_invest'=>number_format($values["usd_invest1"],2,'.',','),
        'gross_amount'=>number_format($values["gross_amount"],2,'.',','), 'iva_amount'=>number_format($values["iva_amount"],2,'.',','), 'ret_isr'=>number_format($values["ret_isr"],2,'.',','),
        'ret_iva'=>number_format($values["ret_iva"],2,'.',','), 'n_amount'=>number_format($values["n_amount"],2,'.',',')]);
    }

    public function calculo($id, $month, $year, $TC, $dllMult, $regime)
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
        $nuc = DB::table('Nuc')->select("cut_date","currency")->where('id',$id)->first();
        // dd($request->year."-".(intval($request->month)-1)."-15");
        // dd($cut_date->cut_date);

        if(intval($nuc->cut_date) > 15)
        {
            // dd("entre a mayor que 15");
            // consultas para corte al día 30
            $data = DB::table('Month_fund')->select("*")
            ->join('Nuc','Nuc.id','=','Month_fund.fk_nuc')
            ->where('fk_nuc',$id)->whereMonth('apply_date',$month)
            ->whereYear('apply_date',$year)->whereNull('Month_fund.deleted_at')->get();

            if($data->isEmpty())
            {
                $fecha = $year.'/'.$month.'/01';
                $data = DB::table('Month_fund')->select('*')
                ->join('Nuc','Nuc.id','=','fk_nuc')
                ->where('fk_nuc',$id)->where('apply_date','<',$fecha)
                ->whereNull('Month_fund.deleted_at')
                ->orderByRaw('Month_fund.id DESC')->first();

                if($data == NULL)
                {
                    $b_amount = 0;
                }
                else
                {
                    $b_amount = $data->new_balance;
                }
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
            ->where('fk_nuc',$id)
            ->whereBetween('apply_date', [$year."-".(intval($month)-1)."-15", $year."-".(intval($month))."-15"])
            ->whereNull('Month_fund.deleted_at')->get();
            if($data->isEmpty())
            {
                $fecha = $year.'/'.(intval($month)-1).'/15';
                $data = DB::table('Month_fund')->select('*')
                ->join('Nuc','Nuc.id','=','fk_nuc')
                ->where('fk_nuc',$id)->where('apply_date','<',$fecha)
                ->whereNull('Month_fund.deleted_at')
                ->orderByRaw('Month_fund.id DESC')->first();
                if($data == null)
                {
                    $b_amount = 0;
                }
                else
                {
                    $b_amount = $data->new_balance;
                }
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
                        if($month == ($month - 1))
                        {
                            $b_amount = $data->new_balance;
                        }
                        else if($month == $month)
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
            $dll_conv = $b_amount / $TC; //si es en pesos, ponemos valor en usd

        }else{
            $dll_conv = $b_amount; //si es en dolares, se queda igual

        }

        $usd_invest = $dll_conv/5000; //por cada 5000 sobre el monto invertido
        $usd_invest1 = $usd_invest*$dllMult; //se multiplica por 10 el resultado obtenido

        $gross_amount = $usd_invest1 * $TC; //monto bruto

        $iva_amount = $gross_amount * .16; // iva del monto bruto

        if($regime == 1)
            $ret_isr = $gross_amount *.10; //isr del monto bruto
        else
            $ret_isr = $gross_amount *.0125;

        $ret_iva = 2*$iva_amount; //retencion de iva
        $ret_iva = $ret_iva/3; //retencion del iva

        $n_amount= ($gross_amount + $iva_amount) - ($ret_isr + $ret_iva); //Monto neto

        $values = array("b_amount"=>$b_amount,'dll_conv'=>$dll_conv,'usd_invest1'=>$usd_invest1,
        'gross_amount'=>$gross_amount, 'iva_amount'=>$iva_amount, 'ret_isr'=>$ret_isr,
        'ret_iva'=>$ret_iva, 'n_amount'=>$n_amount);
        // dd($values);

        return($values);
    }

    public function update(Request $request)
    {
        $client = User::where('id',$request->id)->update(['regime'=>$request->regime]);
        return response()->json(['status'=>true, 'message'=>"Régimen Actualizado"]);
    }
}

