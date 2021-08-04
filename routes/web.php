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

// aseguradoras
Route::get('admin/insurance/insurances/GetInfo/{id}','InsuranceController@GetInfo')->name('insurance.GetInfo');
Route::resource('admin/insurance/insurances', 'InsuranceController');

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

//Tipo de solicitud
Route::resource('admin/applications/application', 'ApplicationsController');
Route::get('admin/applications/application/GetInfo/{id}','ApplicationsController@GetInfo')->name('application.GetInfo');

// moneda
Route::resource('admin/currency/currencies', 'CurrencyController');
Route::get('admin/currency/currencies/GetInfo/{id}','CurrencyController@GetInfo')->name('currencies.GetInfo');

// Ramo
Route::resource('admin/branch/branches', 'BranchController');
Route::get('admin/branch/branches/GetInfo/{id}','BranchController@GetInfo')->name('branches.GetInfo');

// Cálculo de Cobro
Route::resource('admin/charge/charges', 'ChargeController');
Route::get('admin/charge/charges/GetInfo/{id}','ChargeController@GetInfo')->name('charges.GetInfo');

// Formas de pago
Route::resource('admin/payment_forms/payment_form', 'PaymentFormsController');
Route::get('admin/payment_forms/payment_form/GetInfo/{id}','PaymentFormsController@GetInfo')->name('payment_form.GetInfo');

// ------------------------------------Proceso OT--------------------------------------------------
// Proceso Iniciales
Route::resource('processes/OT/Initials/initial', 'InitialController');
Route::get('processes/OT/Initials/initial/GetInfo/{id}','InitialController@GetInfo')->name('initial.GetInfo');
Route::post('processes/OT/Initials/initial/updateStatus', 'InitialController@updateStatus')->name('initial.updateStatus');

// Proceso Servicios
Route::resource('processes/OT/services/service', 'ServicesController');
Route::get('processes/OT/services/service/GetInfo/{id}','ServicesController@GetInfo')->name('service.GetInfo');
Route::post('processes/OT/services/service/updateStatus', 'ServicesController@updateStatus')->name('service.updateStatus');

// Proceso Reembolsos
Route::resource('processes/OT/refunds/refunds', 'RefundsController');
Route::get('processes/OT/refunds/refunds/GetInfo/{id}','RefundsController@GetInfo')->name('refunds.GetInfo');
Route::post('processes/OT/refunds/refunds/updateStatus', 'RefundsController@updateStatus')->name('refunds.updateStatus');
