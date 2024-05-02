<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Globals extends Model
{
    use SoftDeletes;

    protected $table = "Globals";
    protected $fillable =["name","val"];
    protected $dates = ["deleted_at"];
}
