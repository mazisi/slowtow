<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DuplicateOriginalsController;

        Route::get('/duplicate-originals',[DuplicateOriginalsController::class,'index'])->name('dup_originals');

        Route::get('/view-duplicate-original/{slug}',[DuplicateOriginalsController::class,'view_duplicate'])->name('view_duplicate_original');
        
        Route::post('/submit-duplicate-original',[DuplicateOriginalsController::class,'store']);

        // Route::patch('/update-licence/{slug}',[LicenceController::class,'update'])->name('update_licence');

        // Route::delete('/delete-licence/{slug}',[LicenceController::class,'destroy'])->name('delete_licence');

        // Route::patch('/update-licence-active-status/{slug}',[LicenceController::class,'updateActiveStatus']);