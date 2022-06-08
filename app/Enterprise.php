<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;

    protected $table = "Enterprise";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
