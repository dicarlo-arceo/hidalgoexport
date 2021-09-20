<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nuc extends Model
{
    protected $table = "Month_fund";
    protected $fillable =["nuc","fk_client"];
}
