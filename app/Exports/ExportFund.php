<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class ExportFund implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        // dd($this->id);
        $movimientos = DB::table('Month_fund')->select("nuc",DB::raw('CONCAT(Client.name," ",firstname," ",lastname) AS name'),
        'apply_date',DB::raw('IFNULL(auth_date, "No Autorizado") as auth'),'prev_balance','new_balance','currency','amount','type')
            ->join('Nuc',"Nuc.id","=","fk_nuc")
            ->join('Client',"Nuc.fk_client","=","Client.id")
            ->where('fk_nuc',$this->id)->get();

        return $movimientos;
    }
}
