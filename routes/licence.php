<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\PagePreviews\LicencePreviewController;
use App\Http\Controllers\Wholesale\WholesaleController;

        Route::get('/licences',[LicenceController::class,'index'])->name('licences');

        Route::get('/create-licence',[LicenceController::class,'create'])->name('create_licence');

        Route::get('/view-licence',[LicenceController::class,'show'])->name('view_licence');

        Route::post('/submit-licence',[LicenceController::class,'store'])->name('submit_licence');

        Route::patch('/update-licence/{slug}',[LicenceController::class,'update'])->name('update_licence');

        Route::delete('/delete-licence/{slug}',[LicenceController::class,'destroy'])->name('delete_licence');

        Route::patch('/update-licence-active-status/{slug}',[LicenceController::class,'updateActiveStatus']);

        Route::patch('/abandon-licence/{slug}',[LicenceController::class,'abandonLicence']);
        
        Route::get('/preview-licence/{slug}',[LicencePreviewController::class,'preview']);
