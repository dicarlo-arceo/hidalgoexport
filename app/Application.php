<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    //
    use SoftDeletes;

    protected $table = "Applications";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
