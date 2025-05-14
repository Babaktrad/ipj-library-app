<?php

use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\SubscribersController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return 'Hello! from Library App.';
});

Route::group(['middleware' => 'api', 'as' => 'api.'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')->name('logout');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::group(['prefix' => 'books', 'as' => 'books.'], function () {
            Route::get('list', [BooksController::class, 'list'])->name('list');
            Route::post('store', [BooksController::class, 'store'])->name('store');
            Route::put('update', [BooksController::class, 'update'])->name('update');
            Route::delete('delete', [BooksController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'subscribers', 'as' => 'subscribers.'], function () {
            Route::get('list', [SubscribersController::class, 'list'])->name('list');
            Route::post('store', [SubscribersController::class, 'store'])->name('store');
            Route::put('update', [SubscribersController::class, 'update'])->name('update');
            Route::delete('delete', [SubscribersController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'reservation', 'as' => 'reservation.'], function () {
            Route::post('reserve', [ReservationController::class, 'reserve'])->name('reserve');
            Route::post('free', [ReservationController::class, 'free'])->name('free');
            Route::group(['prefix' => 'subscriber', 'as' => 'subscriber.'], function () {
                Route::get('{subscriber_id}/history', [ReservationController::class, 'history'])->name('history');
            });
            Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
                Route::get('book/{book_id}', [ReservationController::class, 'book'])->name('book');
            });
        });
    });
});
