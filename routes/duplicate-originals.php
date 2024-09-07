<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DuplicateOriginalsController;

        Route::get('/duplicate-originals',[DuplicateOriginalsController::class,'index'])->name('dup_originals');

        Route::get('/view-duplicate-original/{slug}',[DuplicateOriginalsController::class,'view_duplicate'])->name('view_duplicate_original');
        
        Route::post('/submit-duplicate-original',[DuplicateOriginalsController::class,'store']);

        Route::patch('/update-duplicate_original',[DuplicateOriginalsController::class,'updateStage']);

        Route::post('/submit-duplicate_original-document',[DuplicateOriginalsController::class,'uploadDocument']);

        Route::delete('/delete-duplicate_original-document/{id}/{prevStage}',[DuplicateOriginalsController::class,'deleteDocument']);

        Route::patch('/update-duplicate_original-date/{id}',[DuplicateOriginalsController::class,'updateDate']);

        Route::delete('/delete-duplicate-original/{slug}',[DuplicateOriginalsController::class,'destroy']);

        Route::post('/abandon-duplicate-original/{slug}',[DuplicateOriginalsController::class,'abandon']);