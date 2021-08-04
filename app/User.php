<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ["deleted_at"];
    protected $table = "users";
    protected $fillable = [
        'fk_profile','name','firstname','lastname', 'email','cellphone', 'password','code_1','code_2','code_3','code_4','code_5'
        ,'code_6','code_7','code_8','code_9','code_10'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function agent_codes()
    {
        return $this->hasMany('App\AgentCode','fk_user','id');
    }

    public static function user_id(){
        return \Auth::user()->id;
    }
    public static function findUser(){
        $id = self::user_id();
        $user = User::find($id);
        return $user;
    }
    public static function findProfile(){
        $user = self::findUser();
        $fk_profile = $user->fk_profile;
        return $fk_profile;
    }

    public function profile(){
        return $this->hasOne('App/Profile', 'id','fk_profile');
    }
}
