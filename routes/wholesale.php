<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wholesale\WholesaleController;
use App\Http\Controllers\Wholesale\WholesaleDocumentController;


Route::get('/process-wholesale',[WholesaleController::class,'show'])->name('view_wholesale');
Route::get('/view-wholesale-licence',[WholesaleController::class,'view_licence'])->name('view_wholesale_licence');
Route::post('/merge-wholesale-alteration-documents/{alteration_id}',[WholesaleDocumentController::class,'merge']);
