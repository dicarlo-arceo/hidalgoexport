<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Permission extends Model
{
    protected $table="Permissions";
    protected $fillable=['fk_profile','fk_section','view','addition','modify','erase'];

    public function section()
    {
        return $this->belongsTo('App\Section');
    }
    public function profile()
    {
        return $this->hasMany('App\Profile');
    }
    public static function permView($fk_profile, $fk_section){
        $perm = Permission::where('fk_profile','=',$fk_profile)->where('fk_section','=',$fk_section)->where('view','=',1)->count();
        return $perm;
    }

    public static function permBtns($fk_profile, $fk_section){

        $perm_btn = Permission::where('fk_profile','=',$fk_profile)->where('fk_section','=',$fk_section)->select('addition','modify','erase')->get()->first();
        return $perm_btn;
    }
    public static function findProfile(){
        $user = self::findUser();
        $fk_profile = $user->fk_profile;
        return $fk_profile;
    }

    public static function  updatePermission($fk_profile,$fk_section,$btn,$reference){
        // dd($fk_profile,$fk_section,$btn,$reference);
        $perm = Permission::where('fk_profile','=',$fk_profile)->where('fk_section','=',$fk_section)->count();
        // dd($perm);
        //existe-editar permiso
        if($perm>0){
              $per = Permission::where('fk_profile','=',$fk_profile)->where('fk_section','=',$fk_section)->pluck('id');
            //   dd($per);
            switch ($btn) {
                case 0://VER
                    $search_id = Permission::find($per[0]);
                    // dd($search_id);
                    if($search_id->view==0){
                        $search_id->view=1;
                        // dd($search_id->view);

                    }else if($search_id->view==1){
                        $search_id->view=0;
                        // dd($search_id->view);
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('Permissions as perm')
                            ->join('Sections','Sections.id','=','perm.fk_section')
                            ->where(['Sections.reference'=>$reference,'view'=>1,'fk_profile'=>$fk_profile])
                            ->count();
                        $perm =   DB::table('Permissions as perm')
                            ->join('Sections','Sections.id','=','perm.fk_section')
                            ->where(['fk_section'=>$reference,'view'=>1,'fk_profile'=>$fk_profile])
                            ->count();
                            // dd($permisions);
                        if($permisions>=1 && $perm == 0){

                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            // dd($findPermisions);
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                            // dd($permisions,"view 1");
                        }
                        else{
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=0;
                            $update->save();
                            // dd($permisions,"view 0");
                        }
                    }

                    break;

                case 1://AGREGAR
                    // dd($per);
                    $search_id = Permission::find($per[0]);
                    // dd($search_id->add);
                    if($search_id->addition==0){
                        $search_id->addition=1;
                    }else if($search_id->addition==1){
                        $search_id->addition=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('Permissions as perm')
                            ->join('Sections','Sections.id','=','perm.fk_section')
                            ->where(['Sections.reference'=>$reference,'view'=>1,'fk_profile'=>$fk_profile])
                            ->count();
                        if($permisions>1){
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;
                case 2://EDITAR
                    $search_id = Permission::find($per[0]);

                    if($search_id->modify==0){
                        $search_id->modify=1;
                    }else if($search_id->modify==1){
                        $search_id->modify=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('Permissions as perm')
                            ->join('Sections','Sections.id','=','perm.fk_section')
                            ->where(['Sections.reference'=>$reference,'view'=>1,'fk_profile'=>$fk_profile])
                            ->count();
                        if($permisions>1){
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            // dd($reference, $fk_profile);
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            // dd($findPermisions);
                            $update = Permission::find($findPermisions[0]);
                            // dd($update);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;
                case 3://ELIMINAR
                    $search_id = Permission::find($per[0]);
                    if($search_id->erase==0){
                        $search_id->erase=1;
                    }else if($search_id->erase==1){
                        $search_id->erase=0;
                    }
                    $search_id->save();
                    if($reference!="undefined"){
                        $permisions =   DB::table('Permissions as perm')
                            ->join('Sections','Sections.id','=','perm.fk_section')
                            ->where(['Sections.reference'=>$reference,'view'=>1,'fk_profile'=>$fk_profile])
                            ->count();
                        if($permisions>1){
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=1;
                            $update->save();
                        }
                        else{
                            $findPermisions = Permission::where('fk_section','=',$reference)->where('fk_profile','=',$fk_profile)->pluck('id');
                            $update = Permission::find($findPermisions[0]);
                            $update->view=0;
                            $update->save();
                        }
                    }

                    break;
            }
        }

    }
}
