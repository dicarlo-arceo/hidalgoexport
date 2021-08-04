<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\Permission;
use App\Section;
use App\User;
use DB;

class PermissionsController extends Controller
{
    public function index(){
        $profiles = Profile::pluck('name','id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile,7);
        $perm_btn =Permission::permBtns($profile,7);
        $padre =Section::whereRaw('id = reference')->orderBy('order','ASC')->get();
        // dd($padre);
        $hijos = Section::whereRaw('id!=reference')->orderBy('order','ASC')->get();
        // dd($hijos, $padre);
        if($perm==0) {
            return redirect()->route('home');
        } else {

            return view('admin.permission.permissions', compact('perm_btn','profile','padre','hijos','profiles'));
        }
    }
    public function edit($id){
        $per = Permission::where('fk_profile','=',$id)->get();
        return response()->json($per);
    }
    public function update_store(Request $request){
        // dd($request->all());
        // dd($fk_section);
        $perm = Permission::where('fk_profile','=',$request->id)->where('fk_section','=',$request->section)->count();
        // dd($perm);
        if($perm>0){
            // dd($request->all());
            // dd($request->id, $request->section, $request->btn, $request->reference);
            Permission::updatePermission($request->id, $request->section, $request->btn, $request->reference);
        }else{//no existe-crear permiso
            switch ($request->btn) {
                case 0://VER
                    $per_ = new Permission();
                    $per_ ->fk_profile=$request->id;
                    $per_ ->fk_section=$request->section;
                    $per_ ->view=1;
                    $per_->save();
                    $permisions =   DB::table('Permissions as perm')
                        ->join('Sections','Sections.id','=','perm.fk_section')
                        ->where(['Sections.reference'=>$request->reference,'view'=>1,'fk_profile'=>$request->id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('fk_section','=',$request->reference)->where('fk_profile','=',$request->id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->fk_profile=$request->id;
                            $createPermision->fk_section=$request->reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }
                    break;

                case 1://AGREGAR
                    // dd("entre");
                    $per_ = new Permission();
                    $per_ ->fk_profile=$request->id;
                    $per_ ->fk_section=$request->section;
                    $per_ ->view=1;
                    $per_ ->addition=1;
                    $per_->save();
                    $perms = Permission::where('fk_profile','=',$request->id)->where('fk_section','=',$request->section)->pluck('fk_section');
                    // dd($perms);
                    $permisions =   DB::table('Permissions as perm')
                        ->join('Sections','Sections.id','=','perm.fk_section')
                        ->where(['Sections.reference'=>$request->reference,'view'=>1,'fk_profile'=>$request->id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('fk_section','=',$request->reference)->where('fk_profile','=',$request->id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->fk_profile=$request->id;
                            $createPermision->fk_section=$request->reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }
                    return response()->json(['data'=>$perms]);
                    break;
                case 2://EDITAR
                    // dd($btn);
                    $per_ = new Permission();
                    $per_ ->fk_profile=$request->id;
                    $per_ ->fk_section=$request->section;
                    $per_ ->view=1;
                    $per_ ->modify=1;
                    $per_->save();
                    $perms = Permission::where('fk_profile','=',$request->id)->where('fk_section','=',$request->section)->pluck('fk_section');

                    $permisions =   DB::table('Permissions as perm')
                        ->join('Sections','Sections.id','=','perm.fk_section')
                        ->where(['Sections.reference'=>$request->reference,'view'=>1,'fk_profile'=>$request->id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('fk_section','=',$request->reference)->where('fk_profile','=',$request->id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->fk_profile=$request->id;
                            $createPermision->fk_section=$request->reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }

                    return response()->json(['data'=>$perms]);
                    break;
                case 3://ELIMINAR
                    $per_ = new Permission();
                    $per_ ->fk_profile=$request->id;
                    $per_ ->fk_section=$request->section;
                    $per_ ->view=1;
                    $per_ ->erase=1;
                    $per_->save();
                    $perms = Permission::where('fk_profile','=',$request->id)->where('fk_section','=',$request->section)->pluck('fk_section');

                    $permisions =   DB::table('Permissions as perm')
                        ->join('Sections','Sections.id','=','perm.fk_section')
                        ->where(['Sections.reference'=>$request->reference,'view'=>1,'fk_profile'=>$request->id])
                        ->count();
                    if($permisions>0){
                        $findPermisions = Permission::where('fk_section','=',$request->reference)->where('fk_profile','=',$request->id)->get();
                        if($findPermisions->count() == 0){
                            $createPermision = new Permission();
                            $createPermision->fk_profile=$request->id;
                            $createPermision->fk_section=$request->reference;
                            $createPermision->view=1;
                            $createPermision->save();
                        }
                    }
                    return response()->json(['data'=>$perms]);
                    break;
            }
        }

    }
}
