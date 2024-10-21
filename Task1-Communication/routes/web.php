<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GoogleSheetsController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/create-sheet', [TaskController::class, 'createGoogleSheet'])->name('create-sheet');
Route::get('/google/login', [GoogleSheetsController::class, 'login'])->name('google.login');
Route::get('/google/callback', [GoogleSheetsController::class, 'callback'])->name('google.callback');
