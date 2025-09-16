<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

//rutas para ingreso a la app
Route::get('/',[App\Http\Controllers\HomeController::class, 'index']); //Raiz

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); //Home
