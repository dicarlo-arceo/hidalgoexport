<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = "Orders";
    protected $fillable =["order_number","address","fk_project","fk_user","exc_rate","percentage","expenses","currency","designer"];
    protected $dates = ["deleted_at"];
}
