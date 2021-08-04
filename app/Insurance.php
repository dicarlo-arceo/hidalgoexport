<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use SoftDeletes;

    protected $table = "Insurance";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
