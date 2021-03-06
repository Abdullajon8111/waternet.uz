<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
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

Auth::routes(['register' => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users_create');

    Route::get('/clients', [App\Http\Controllers\HomeController::class, 'clients'])->name('clients');
    
    Route::post('/client-edit/{id}', [App\Http\Controllers\HomeController::class, 'client_edit'])->name('client_edit');
    Route::post('/client-delete/{id}', [App\Http\Controllers\HomeController::class, 'delete_client'])->name('delete_client');

    Route::post('/zakaz', [App\Http\Controllers\HomeController::class, 'zakaz'])->name('zakaz');
    Route::get('/add-client', [App\Http\Controllers\HomeController::class, 'add_client_page'])->name('add_client_page');
    Route::post('/add-client-success', [App\Http\Controllers\HomeController::class, 'add_client'])->name('add_client');
    
    Route::post('/add-region', [App\Http\Controllers\HomeController::class, 'add_region'])->name('add_region');
    
    Route::get('/orders', [App\Http\Controllers\HomeController::class, 'orders'])->name('orders');
   
    Route::get('/products', [App\Http\Controllers\HomeController::class, 'products'])->name('products');
    Route::post('/add-product', [App\Http\Controllers\HomeController::class, 'add_product'])->name('add_product');

    Route::get('/workers', [App\Http\Controllers\HomeController::class, 'workers'])->name('workers');

    Route::get('/sities', [App\Http\Controllers\HomeController::class, 'sities'])->name('sities');
    Route::post('/add-sity', [App\Http\Controllers\HomeController::class, 'add_city'])->name('add_city');

    
    Route::get('/results', [App\Http\Controllers\HomeController::class, 'results'])->name('results');
    Route::post('/add-order/{id}', [App\Http\Controllers\HomeController::class, 'add_order'])->name('add_order');
    Route::post('/success-order/{id}', [App\Http\Controllers\HomeController::class, 'success_order'])->name('success_order');

    
    Route::get('/location', [App\Http\Controllers\HomeController::class, 'location'])->name('location');
    Route::get('/view-location/{id}', [App\Http\Controllers\ClientController::class, 'view_location'])->name('view_location');

    
    Route::post('/client-price/{id}', [App\Http\Controllers\ClientController::class, 'client_price'])->name('client_price');
    Route::post('/client-container/{id}', [App\Http\Controllers\ClientController::class, 'client_container'])->name('client_container');

    Route::post('/client-price-edit/{id}', [App\Http\Controllers\ClientController::class, 'client_price_edit'])->name('client_price_edit');
    Route::post('/client-price-delete/{id}', [App\Http\Controllers\ClientController::class, 'client_price_delete'])->name('client_price_delete');
    Route::post('/client-container-edit/{id}', [App\Http\Controllers\ClientController::class, 'client_container_edit'])->name('client_container_edit');
    Route::post('/client-container-delete/{id}', [App\Http\Controllers\ClientController::class, 'client_container_delete'])->name('client_container_delete');


    Route::post('/edit-product/{id}', [App\Http\Controllers\ClientController::class, 'edit_product'])->name('edit_product');
    Route::post('/delete-product/{id}', [App\Http\Controllers\ClientController::class, 'delete_product'])->name('delete_product');

    Route::get('/succ-ord-view/{id}', [App\Http\Controllers\ClientController::class, 'success_order_view'])->name('success_order_view');

    Route::get('/regions/regions', [App\Http\Controllers\HomeController::class, 'regions'])->name('regions');
    Route::get('/regions/cities', [App\Http\Controllers\HomeController::class, 'cities'])->name('cities');
    Route::post('/regions/edit-region/{id}', [App\Http\Controllers\HomeController::class, 'edit_region'])->name('edit_region');
    Route::post('/regions/delete-region/{id}', [App\Http\Controllers\HomeController::class, 'delete_region'])->name('delete_region');
    Route::post('/regions/edit-city/{id}', [App\Http\Controllers\HomeController::class, 'edit_city'])->name('edit_city');
    Route::post('/regions/delete-city/{id}', [App\Http\Controllers\HomeController::class, 'delete_city'])->name('delete_city');

    Route::get('/soldproducts/{id}', [App\Http\Controllers\HomeController::class, 'soldproducts'])->name('soldproducts');
    Route::get('/take-client-container/{id}', [App\Http\Controllers\HomeController::class, 'take_client_container'])->name('take_client_container');
    
    Route::get('/warehouse', [App\Http\Controllers\ClientController::class, 'entry_container'])->name('entry_container');
    Route::get('/warehouse/take-products', [App\Http\Controllers\ClientController::class, 'take_products'])->name('take_products');
    Route::get('/warehouse/entry-products', [App\Http\Controllers\ClientController::class, 'entry_products'])->name('entry_products');
    Route::get('/warehouse/entry-container', [App\Http\Controllers\ClientController::class, 'entry_container'])->name('entry_container');
    Route::get('/warehouse/take-container', [App\Http\Controllers\ClientController::class, 'take_container'])->name('take_container');

    
    Route::post('/warehouse/edit-entry-container/{id}', [App\Http\Controllers\ClientController::class, 'edit_entry_container'])->name('edit_entry_container');
    Route::post('/warehouse/delete-entry-container/{id}', [App\Http\Controllers\ClientController::class, 'delete_entry_container'])->name('delete_entry_container');
    Route::post('/warehouse/take-edit-container/{id}', [App\Http\Controllers\ClientController::class, 'take_edit_container'])->name('take_edit_container');
    Route::post('/warehouse/take-delete-container/{id}', [App\Http\Controllers\ClientController::class, 'take_delete_container'])->name('take_delete_container');
    Route::post('/warehouse/edit-entry-product/{id}', [App\Http\Controllers\ClientController::class, 'edit_entry_product'])->name('edit_entry_product');
    Route::post('/warehouse/delete-entry-product/{id}', [App\Http\Controllers\ClientController::class, 'delete_entry_product'])->name('delete_entry_product');
    Route::post('/warehouse/take-edit-product/{id}', [App\Http\Controllers\ClientController::class, 'take_edit_product'])->name('take_edit_product');
    Route::post('/warehouse/take-delete-product/{id}', [App\Http\Controllers\ClientController::class, 'take_delete_product'])->name('take_delete_product');


    Route::post('/warehouse/take-succ-products', [App\Http\Controllers\ClientController::class, 'add_take_product'])->name('add_take_product');
    Route::post('/warehouse/entry-succ-products', [App\Http\Controllers\ClientController::class, 'add_entry_product'])->name('add_entry_product');
    Route::post('/warehouse/entry-succ-container', [App\Http\Controllers\ClientController::class, 'add_entry_container'])->name('add_entry_container');
    Route::post('/warehouse/take-succ-container', [App\Http\Controllers\ClientController::class, 'take_entry_container'])->name('take_entry_container');

    Route::get('/smsmanager/send-message', [App\Http\Controllers\ClientController::class, 'send_message'])->name('send_message');
    Route::get('/smsmanager/success-message', [App\Http\Controllers\ClientController::class, 'success_message'])->name('success_message');
    Route::get('/smsmanager/sms-text', [App\Http\Controllers\ClientController::class, 'sms_text'])->name('sms_text');
    
    Route::get('/traffics-merchant', [App\Http\Controllers\ClientController::class, 'traffic_merchant'])->name('traffic_merchant');

    Route::post('/smsmanager/send-client-message/{id}', [App\Http\Controllers\ClientController::class, 'send_client_message'])->name('send_client_message');

    
    Route::get('/administration/organization/organizations', [App\Http\Controllers\TrafficController::class, 'organizations'])->name('organizations');
    Route::get('/administration/traffics', [App\Http\Controllers\TrafficController::class, 'traffics'])->name('traffics');
    Route::get('/administration/user_organizations', [App\Http\Controllers\TrafficController::class, 'user_organizations'])->name('user_organizations');
    Route::get('/administration/users-admin', [App\Http\Controllers\UserController::class, 'users_admin'])->name('users_admin');

    
    Route::post('/administration/add-traffic', [App\Http\Controllers\TrafficController::class, 'add_traffic'])->name('add_traffic');
    Route::post('/administration/add-organization', [App\Http\Controllers\TrafficController::class, 'add_organization'])->name('add_organization');
    Route::post('/administration/edit-organization/{id}', [App\Http\Controllers\TrafficController::class, 'edit_organization'])->name('edit_organization');
    Route::post('/administration/delete-organization/{id}', [App\Http\Controllers\TrafficController::class, 'delete_organization'])->name('delete_organization');
    Route::post('/administration/edit-traffic/{id}', [App\Http\Controllers\TrafficController::class, 'edit_traffic'])->name('edit_traffic');
    Route::post('/administration/delete-traffic/{id}', [App\Http\Controllers\TrafficController::class, 'delete_traffic'])->name('delete_traffic');

    Route::post('/send-sms', [App\Http\Controllers\ClientController::class, 'send_sms'])->name('send_sms');
    Route::get('/reklama', [App\Http\Controllers\TrafficController::class, 'reklama'])->name('reklama');
    Route::get('/organization/trafficorgan/{id}', [App\Http\Controllers\TrafficController::class, 'trafficorgan'])->name('trafficorgan');
    Route::get('/organization/addpriceorgan/{id}', [App\Http\Controllers\TrafficController::class, 'indexpriceorgan'])->name('addpriceorgan');
    Route::post('/organization/add-success-price-org/{id}', [App\Http\Controllers\TrafficController::class, 'add_price_organization'])->name('add_price_organization');
    Route::post('/organization/add-success-traffic-org/{id}', [App\Http\Controllers\TrafficController::class, 'add_traffic_organization'])->name('add_traffic_organization');
    Route::post('/organization/edit-success-price-org/{id}', [App\Http\Controllers\TrafficController::class, 'edit_price_organization'])->name('edit_price_organization');
    Route::post('/organization/delete-success-price-org/{id}', [App\Http\Controllers\TrafficController::class, 'delete_price_organization'])->name('delete_price_organization');
});
