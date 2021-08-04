<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    //
    use SoftDeletes;

    protected $table = "Branch";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
