<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsUserController;

Route::get('/', fn () => view('welcome'));

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');


Route::get('/login', [SessionsUserController::class, 'create'])->middleware('guest');
Route::post('/login', [SessionsUserController::class, 'store'])->middleware('guest');

Route::post('/logout', [SessionsUserController::class, 'destroy'])->middleware('auth');