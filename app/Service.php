<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = "Services";
    protected $fillable =[
        'fk_agent','entry_date','policy','response_date','download','type','folio','name','record','fk_branch',
        'fk_insurance','fk_status'];
    protected $dates = ["deleted_at"];
}
