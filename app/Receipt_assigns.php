<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt_assigns extends Model
{
    use SoftDeletes;

    protected $table = "Receipt_assigns";
    protected $fillable =["idsOrd","receipt"];
    protected $dates = ["deleted_at"];
}
