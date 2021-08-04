<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;

    protected $table = "Enterprise";
    protected $fillable =[
        'business_name','date','rfc','curp','street','e_num','i_num','suburb','pc',
        'country','state','city','cellphone','email','name_contact','phone_contact'];
    protected $dates = ["deleted_at"];
}
