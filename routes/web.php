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
Route::post('admin/pruebas/prueba/pruebaAbrir', 'PruebasController@pruebaAbrir')->name('prueba.pruebaAbrir');

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
Route::get('admin/project/projects/GetInfoOrder/{id}', 'ProjectsController@GetInfoOrder')->name('projects.GetInfoOrder');
Route::resource('admin/project/projects', 'ProjectsController');

//------------------------------------------------Procesos---------------------------------------------
// ordenes
Route::get('processes/order/orders/GetInfo/{id}','OrdersController@GetInfo')->name('orders.GetInfo');
Route::get('processes/order/orders/GetInfoOrder/{id}','OrdersController@GetInfoOrder')->name('orders.GetInfoOrder');
Route::get('processes/order/orders/GetInfoItem/{id}','OrdersController@GetInfoItem')->name('orders.GetInfoItem');
Route::get('processes/order/orders/OpenSingleOrder/{id}','OrdersController@OpenSingleOrder')->name('orders.OpenSingleOrder');
Route::resource('processes/order/orders', 'OrdersController');
Route::delete('processes/order/orders/DeleteItem/{id}/{idOrder}/{tr}', 'OrdersController@DeleteItem')->name('orders.DeleteItem');
Route::post('processes/order/orders/updateStatus', 'OrdersController@updateStatus')->name('orders.updateStatus');
Route::post('processes/order/orders/updateStatusOrder', 'OrdersController@updateStatusOrder')->name('orders.updateStatusOrder');
Route::post('processes/order/orders/updateItem', 'OrdersController@updateItem')->name('orders.updateItem');
Route::post('processes/order/orders/updateOrder', 'OrdersController@updateOrder')->name('orders.updateOrder');
Route::post('processes/order/orders/deleteFile', 'OrdersController@deleteFile')->name('orders.deleteFile');
Route::post('processes/order/orders/updateTR', 'OrdersController@updateTR')->name('orders.updateTR');
Route::get('processes/order/orders/GetinfoStatus/{id}', 'OrdersController@GetinfoStatus')->name('orders.GetinfoStatus');
Route::get('processes/order/orders/GetinfoStatusOrder/{id}', 'OrdersController@GetinfoStatusOrder')->name('orders.GetinfoStatusOrder');
Route::get('processes/order/orders/GetinfoTR/{id}', 'OrdersController@GetinfoTR')->name('orders.GetinfoTR');
Route::get('processes/order/orders/GetItemsTR/{id}/{tr}', 'OrdersController@GetItemsTR')->name('orders.GetItemsTR');
Route::get('processes/order/orders/GetPDF/{id}/{cellar}/{comition}/{dlls}/{date}/{pkgs}', 'OrdersController@GetPDF')->name('orders.GetPDF');
Route::get('processes/order/orders/ItemsPDF/{order}/{tr}/{cellar}/{comition}/{mxn_total}/{iva}/{mxn_invoice}/{usd_total}/{broker}/{flaginvoice}', 'OrdersController@ItemsPDF')->name('orders.ItemsPDF');
Route::get('processes/order/orders/GetinfoTROrders/{id}', 'OrdersController@GetinfoTROrders')->name('orders.GetinfoTROrders');
Route::get('processes/order/orders/GetPDFCobroTodos/{flag}/{date}/{pkgs}/{tr}/{address}/{ids}', 'OrdersController@GetPDFCobroTodos')->name('orders.GetPDFCobroTodos');
Route::post('processes/order/orders/updateBOAll', 'OrdersController@updateBOAll')->name('orders.updateBOAll');
Route::get('processes/order/orders/GetPDFItemsTodos/{flag}/{tr}/{ids}/{flaginvoice}', 'OrdersController@GetPDFItemsTodos')->name('orders.GetPDFItemsTodos');

