<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $table = "Items";
    protected $fillable =["fk_order","store","item_number","description","back_order","existence","fk_status","net_price","total_price","commentary"];
    protected $dates = ["deleted_at"];
}
