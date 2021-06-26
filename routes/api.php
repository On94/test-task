<?php

use App\Core\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BooksController;

Route::initializeRESTApi();

Route::post('/register', [AuthController::class, 'register']);
Route::post('/log-in', [AuthController::class, 'login']);


Route::post('/book', [BooksController::class, 'store']);
Route::get('/book', [BooksController::class, 'retrieve']);
Route::delete('/book', [BooksController::class, 'delete']);
Route::put('/book', [BooksController::class, 'update']);
