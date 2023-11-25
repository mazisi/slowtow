<?php

use App\Http\Controllers\Wholesale\WholesaleController;
use Illuminate\Support\Facades\Route;


Route::get('/process-wholesale',[WholesaleController::class,'show'])->name('view_wholesale');
