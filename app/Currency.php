<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    //
    use SoftDeletes;

    protected $table = "Currency";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
