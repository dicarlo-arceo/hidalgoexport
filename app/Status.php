<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $table = "Status";
    protected $fillable =["name","fk_section"];
    protected $dates = ["deleted_at"];
}

