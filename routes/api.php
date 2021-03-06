<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(CustomerController::class)->group(function () {
    Route::get('customers', 'index');
    Route::get('customers/{value}','show');
    Route::post('customers','store');
    Route::put('customers/{id}','update');
    Route::delete('customers/{id}','destroy');
    Route::post('customers/import','importFromCSV');
    Route::get('customer/{id}','getCustomerById');
    
}); 

