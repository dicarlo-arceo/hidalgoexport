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
Route::resource('admin/clients/client', 'ClientsController');
Route::get('admin/clients/client/GetInfo/{id}','ClientsController@GetInfo')->name('client.GetInfo');
Route::post('admin/clients/client/SaveOrder','ClientsController@SaveOrder')->name('client.SaveOrder');

// permisos
Route::resource('admin/permission/permissions', 'PermissionsController');
Route::get('admin/permission/permissions/{id}/{id_seccion?}/{btn?}/{reference?}',[ 'uses' => 'PermissionsController@update_store', 'as' => 'admin.permission.update_store']);
Route::post('admin/permission/permissions/update_store','PermissionsController@update_store')->name('permissions.update_store');

// empresas
Route::get('admin/enterprise/enterprises/GetInfo/{id}','EnterpriseController@GetInfo')->name('enterprise.GetInfo');
Route::resource('admin/enterprise/enterprises', 'EnterpriseController');

// proyectos
Route::get('admin/project/projects/GetInfo/{id}','ProjectsController@GetInfo')->name('projects.GetInfo');
Route::resource('admin/project/projects', 'ProjectsController');

//------------------------------------------------Procesos---------------------------------------------
// ordenes
Route::get('processes/order/orders/GetInfo/{id}','OrdersController@GetInfo')->name('orders.GetInfo');
Route::get('processes/order/orders/GetInfoOrder/{id}','OrdersController@GetInfoOrder')->name('orders.GetInfoOrder');
Route::get('processes/order/orders/GetInfoItem/{id}','OrdersController@GetInfoItem')->name('orders.GetInfoItem');
Route::resource('processes/order/orders', 'OrdersController');
Route::delete('processes/order/orders/DeleteItem/{id}/{idOrder}', 'OrdersController@DeleteItem')->name('orders.DeleteItem');
Route::post('processes/order/orders/updateStatus', 'OrdersController@updateStatus')->name('orders.updateStatus');
Route::post('processes/order/orders/updateItem', 'OrdersController@updateItem')->name('orders.updateItem');
Route::post('processes/order/orders/updateOrder', 'OrdersController@updateOrder')->name('orders.updateOrder');
Route::post('processes/order/orders/deleteFile', 'OrdersController@deleteFile')->name('orders.deleteFile');
Route::post('processes/order/orders/updateTR', 'OrdersController@updateTR')->name('orders.updateTR');
Route::get('processes/order/orders/GetinfoStatus/{id}', 'OrdersController@GetinfoStatus')->name('orders.GetinfoStatus');

