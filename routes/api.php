<?php

use App\Http\Controllers\API\V1\BookController;
use App\Http\Controllers\API\V1\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    Route::controller(BookController::class)
        ->group(function () {
            Route::get('/books', 'index')->name('books.list');
            Route::get('/books/{book}', 'show')->name('book.show');
            Route::patch('/books/{book}', 'updateStatus')->name('book.update');
        });

     Route::middleware('ensureJsno')->controller(CustomerController::class)
         ->group( function () {
            Route::get('/customers', 'index')->name('customers.list');
            Route::post('/customers', 'store')->name('customers.create');
            Route::get('/customers/{customer}', 'show')->name('customers.show');
            Route::put('/customers/{customer}', 'update')->name('customers.update');
            Route::delete('/customers/{customer}', 'destroy')->name('customers.delete');
         });
});
