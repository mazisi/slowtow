<?php

use App\Http\Controllers\Wholesale\WholesaleController;
use Illuminate\Support\Facades\Route;


Route::get('/process-wholesale',[WholesaleController::class,'show'])->name('view_wholesale');
Route::get('/view-wholesale-licence',[WholesaleController::class,'view_licence'])->name('view_wholesale_licence');
