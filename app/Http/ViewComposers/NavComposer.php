<?php
namespace App\Http\ViewComposers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Route as Route;
use App\Section as Section;
use App\User as User;

class NavComposer
{
    public function compose($view)
    {
        if (Auth::check())
        {
            $profile = User::findProfile();
          //   $super = User::findUser()->super;
             // 22-11-2019
             // nueva lógica para cargar el menú
             $sections =  DB::table('Permissions')
             ->select(
                 'sec.id',
                 'sec.section AS section',
                 'sec.icon',
                 'sec.description'
             )
             ->join('Sections as sec',function($join){
                 $join->on('sec.id','=','Permissions.fk_section')
                 ->where('sec.type','=','SECTION');
             })
             ->where('Permissions.fk_profile','=',$profile)
             ->where('Permissions.view','=',1)
             ->groupBy('sec.id')
             ->orderBy('sec.order','asc')
             ->get();
             $sections2 = DB::table('Permissions')
                 ->select(
                     'sec.id',
                     'sec.section AS section',
                     'sec.icon',
                     'sec.description'
                 )
                 ->join('Sections as modules',function($join){
                     $join->on('modules.id','=','Permissions.fk_section')
                     ->where('modules.type','=','MODULE');
                 })
                 ->join('Sections as sub_sections',function($join){
                     $join->on('sub_sections.id','=','modules.padre')
                     ->where('sub_sections.type','=','SUBSECTION');
                 })
                 ->join('Sections as sec',function($join){
                     $join->on('sec.id','=','sub_sections.padre')
                     ->where('sec.type','=','SECTION');
                 })
                 ->where('Permissions.fk_profile','=',$profile)
                 ->where('Permissions.view','=',1)
                 ->groupBy('sec.id')
                 ->orderBy('sec.order','asc')
                 ->get();
             $subsections = DB::table('Permissions')
             ->select(
                 'sec.order',
                 'sec.id AS padre_id',
                 'sec.section AS section',
                 'sub_sections.order as sub_order',
                 'sub_sections.id as sub_fk_section',
                 'sub_sections.section AS subsection',
                 'modules.id AS module_id',
                 'modules.section AS module',
                 'modules.url',
                 'Permissions.fk_profile'
             )
             ->join('Sections as modules',function($join){
                 $join->on('modules.id','=','Permissions.fk_section')
                 ->where('modules.type','=','MODULE');
             })
             ->join('Sections as sub_sections',function($join){
                 $join->on('sub_sections.id','=','modules.padre')
                 ->where('sub_sections.type','=','SUBSECTION');
             })
             ->join('Sections as sec',function($join){
                 $join->on('sec.id','=','sub_sections.padre')
                 ->where('sec.type','=','SECTION');
             })
             ->where('Permissions.fk_profile','=',$profile)
             ->where('Permissions.view','=',1)
             ->groupBy('sec.id','sub_sections.id')
             ->orderBy('sec.order','asc')
             ->orderBy('sub_sections.order','asc')
             ->get();
            // dd($sections,$sections2,$subsections);
             $user_permissions = DB::table('Permissions')
                 ->select(
                     'modules.padre AS padre_id',
                     'modules.reference AS reference',
                     'modules.id AS module_id',
                     'modules.section AS module',
                     'modules.url',
                     'Permissions.fk_profile'
                 )
                 ->join('Sections as modules',function($join){
                     $join->on('modules.id','=','Permissions.fk_section')
                     ->where('modules.type','=','MODULE');
                 })
                 ->where('Permissions.fk_profile','=',$profile)
                 ->where('Permissions.view','=',1)
                 ->orderBy('modules.order','asc')
                 ->get();
                     // dd($sections,$user_permissions);
                 $view->with('secciones', $sections)
                 ->with('subsections',$subsections)
                 ->with('user_permissions',$user_permissions);


        }
    }
}
