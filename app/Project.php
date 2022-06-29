<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = "Projects";
    protected $fillable =["name"];
    protected $dates = ["deleted_at"];
}
