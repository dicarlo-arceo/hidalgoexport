<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prueba extends Model
{
    use SoftDeletes;

    protected $table = "Profiles";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
