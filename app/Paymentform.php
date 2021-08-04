<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paymentform extends Model
{
    use SoftDeletes;

    protected $table = "Payment_form";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
