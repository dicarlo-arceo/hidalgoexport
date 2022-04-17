<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $table = "Client";
    protected $fillable =[
        'name','firstname','lastname','birth_date','rfc','curp','gender','cellphone','email'];
        // 'marital_status','street','e_num','i_num','suburb','pc',
        // 'country','state','city',
    protected $dates = ["deleted_at"];
}
