<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthFund extends Model
{
    use SoftDeletes;

    protected $table = "Month_fund";
    protected $fillable =["fk_nuc", "type", "amount", "prev_balance", "new_balance", "apply_date", "auth_date", "pay_date"];
    protected $dates = ["deleted_at"];
}
