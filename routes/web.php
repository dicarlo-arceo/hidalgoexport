<?php

use Illuminate\Support\Facades\Route;

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
Route::post('admin/client/client/saveEnterprise', 'ClientsController@saveEnterprise')->name('client.saveEnterprise');
Route::get('admin/client/client/GetInfoE/{id}','ClientsController@GetInfoE')->name('client.GetInfoE');
Route::post('admin/client/client/updateEnterprise', 'ClientsController@updateEnterprise')->name('client.updateEnterprise');
Route::delete('admin/client/client/destroyEnterprise/{id}','ClientsController@destroyEnterprise')->name('cliente.destroyEnterprise');

// permisos
Route::resource('admin/permission/permissions', 'PermissionsController');
Route::get('admin/permission/permissions/{id}/{id_seccion?}/{btn?}/{reference?}',[ 'uses' => 'PermissionsController@update_store', 'as' => 'admin.permission.update_store']);
Route::post('admin/permission/permissions/update_store','PermissionsController@update_store')->name('permissions.update_store');

// ------------------------------------------ Procesos de fondos --------------------------------------------------
// fondo mensual
Route::resource('funds/monthfund/monthfunds', 'MonthFundsController');
Route::get('funds/monthfund/monthfunds/GetInfo/{id}','MonthFundsController@GetInfo')->name('monthfunds.GetInfo');
