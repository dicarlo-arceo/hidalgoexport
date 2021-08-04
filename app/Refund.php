<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    protected $table = "Refunds";
    protected $fillable =[
        'fk_agent','folio','contractor','fk_insurance','entry_date','policy','fk_status','insured',
        'sinister','amount','payment_form'];
    protected $dates = ["deleted_at"];
}
