<?php

use Illuminate\Support\Facades\Route;
use App\Exports\ExportFund;
use Maatwebsite\Excel\Facades\Excel;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth/login');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// perfiles
Route::get('admin/profile/profiles/GetInfo/{id}','ProfilesController@GetInfo')->name('profiles.GetInfo');
Route::resource('admin/profile/profiles', 'ProfilesController');

// prueba
Route::get('admin/pruebas/prueba/GetInfo/{id}','PruebasController@GetInfo')->name('prueba.GetInfo');
Route::resource('admin/pruebas/prueba', 'PruebasController');

// usuarios
Route::resource('admin/users/user', 'UsersController');
Route::get('admin/users/user/GetInfo/{id}','UsersController@GetInfo')->name('user.GetInfo');

// clientes
Route::resource('admin/client/client', 'ClientsController');
Route::get('admin/client/client/GetInfo/{id}','ClientsController@GetInfo')->name('client.GetInfo');
Route::post('admin/client/client/SaveNuc','ClientsController@SaveNuc')->name('client.SaveNuc');

// permisos
Route::resource('admin/permission/permissions', 'PermissionsController');
Route::get('admin/permission/permissions/{id}/{id_seccion?}/{btn?}/{reference?}',[ 'uses' => 'PermissionsController@update_store', 'as' => 'admin.permission.update_store']);
Route::post('admin/permission/permissions/update_store','PermissionsController@update_store')->name('permissions.update_store');

// ------------------------------------------ Procesos de fondos --------------------------------------------------
// fondo mensual
Route::resource('funds/monthfund/monthfunds', 'MonthFundsController');
Route::get('funds/monthfund/monthfunds/GetInfo/{id}','MonthFundsController@GetInfo')->name('monthfunds.GetInfo');
Route::get('funds/monthfund/monthfunds/GetInfoLast/{id}','MonthFundsController@GetInfoLast')->name('monthfunds.GetInfoLast');
Route::post('funds/monthfund/monthfunds/updateStatus', 'MonthFundsController@updateStatus')->name('monthfunds.updateStatus');
Route::post('funds/monthfund/monthfunds/updateAuth', 'MonthFundsController@updateAuth')->name('monthfunds.updateAuth');
Route::get('funds/monthfund/monthfunds/GetNuc/{id}','MonthFundsController@GetNuc')->name('monthfunds.GetNuc');
Route::get('funds/monthfund/monthfunds/ExportFunds/{id}','MonthFundsController@ExportFunds');
// Route::get('funds/monthfund/monthfunds/GetNuc/{id}','MonthFundsController@GetNuc')->name('monthfunds.GetNuc');

// -------------------------------------------------- Asignación de clientes--------------------------------------------

Route::resource('admin/assiment/assigment', 'AssigmentController');
Route::get('admin/assiment/assigment/Viewclients/{id}','AssigmentController@Viewclients')->name('assigment.Viewclients');
Route::post('admin/assiment/assigment/updateClient', 'AssigmentController@updateClient')->name('assigment.updateClient');

//---------------------------------------------------Comisiones fondo mensual--------------------------------------------

Route::resource('funds/monthlycomission/monthcomission', 'MonthComissionController');
Route::get('funds/monthlycomission/monthcomission/GetInfo/{id}','MonthComissionController@GetInfo')->name('monthcomission.GetInfo');
Route::get('funds/monthlycomission/monthcomission/GetInfoMonth/{id}/{month}/{year}','MonthComissionController@GetInfoMonth')->name('monthcomission.GetInfoMonth');
Route::get('funds/monthlycomission/monthcomission/GetInfoLast/{id}','MonthComissionController@GetInfoLast')->name('monthcomission.GetInfoLast');
Route::get('funds/monthlycomission/monthcomission/ExportPDF/{id}','MonthComissionController@ExportPDF');

