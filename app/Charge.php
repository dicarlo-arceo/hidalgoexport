<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use SoftDeletes;

    protected $table = "Charge";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
