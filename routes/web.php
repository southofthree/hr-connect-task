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

Auth::routes();

Route::get('check', 'TicketController@check')->name('check');

Route::middleware('auth')->group(function() {
    Route::get('', 'TicketController@index')->name('home');

    Route::middleware('client')->group(function() {
        Route::prefix('tickets')->group(function() {
            Route::get('add', 'TicketController@add')->name('tickets.add');
            Route::middleware('can:create,App\Ticket')->post('', 'TicketController@store')->name('tickets.store');
            Route::middleware('can:close,ticket')->post('{ticket}/close', 'TicketController@close')->name('tickets.close');
        });
    });

    Route::middleware('manager')->group(function() {
        Route::post('{ticket}/assign', 'TicketController@assign')->name('tickets.assign');
    });

    Route::prefix('tickets')->group(function() {
        Route::middleware('can:view,ticket')->get('{ticket}', 'TicketController@show')->name('tickets.show');
        Route::middleware('can:respond,ticket')->post('{ticket}', 'TicketController@respond')->name('tickets.respond');
    });
});