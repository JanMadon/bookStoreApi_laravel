<?php

use App\Http\Controllers\API\V1\BookController;
use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\TokenController;
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

    Route::post('/users', [TokenController::class, 'create'])->name('user.create');

    Route::controller(BookController::class)
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('/books', 'index')->name('books.list');
            Route::get('/books/{book}', 'show')->name('book.show');
            Route::patch('/books/{book}', 'updateStatus')->name('book.update');
        });

     Route::controller(CustomerController::class)
        ->middleware('auth:sanctum')
        ->group( function () {
            Route::get('/customers', 'index')->name('customers.list');
            Route::middleware('abilities:create')->post('/customers', 'store')->name('customers.create');
            Route::get('/customers/{customer}', 'show')->name('customers.show');
            Route::middleware('abilities:update')->put('/customers/{customer}', 'update')->name('customers.update');
            Route::middleware('abilities:delete')->delete('/customers/{customer}', 'destroy')->name('customers.delete');
         });
});

// user token:     3|ANoW0YUZLOGDoh5wv2nfIhi4T2u5v6GDonJJX0E9cc3a4350
// admin token:    2|jkdc9Zk9J3Z9uAYbsggEjfBMneLSlfI2R8toceslc079a001

// w postman dajemy Authorization->Bearer Token i wklejamy token.
// uzytkownik tworzy się po uderzeniu GET na /gettoken
// w pliku web.php jest tworzenie usera
