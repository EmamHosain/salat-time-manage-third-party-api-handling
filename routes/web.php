<?php

use App\Http\Controllers\SalatTimeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[SalatTimeController::class,'page'])->name('page');

Route::get('/get-data',[SalatTimeController::class,'index'])->name('index');
Route::get('/single-row',[SalatTimeController::class,'getSingleRow'])->name('get_single_row');