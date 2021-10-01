<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nuc extends Model
{
    use SoftDeletes;

    protected $table = "Nuc";
    protected $fillable =["nuc","currency","estatus","fk_client"];
}
