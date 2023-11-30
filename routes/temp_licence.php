<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemporalLicenceController;
use App\Http\Controllers\TemporalLicenceDocsController;


Route::get('/temp-licences', [TemporalLicenceController::class,'index'])->name('temp_licences');

Route::get('/create-temp-licence', [TemporalLicenceController::class,'create'])->name('create_temp_licence');

Route::post('/submit-temp-licence', [TemporalLicenceController::class,'store'])->name('store_temp_licence');

Route::get('/view-temp-licence/{slug}', [TemporalLicenceController::class,'show'])->name('view_temp_licence');

Route::get('/process-temp-application', [TemporalLicenceController::class,'processApplication']);

Route::patch('/update-prepared-temp-app/{slug}', [TemporalLicenceController::class,'update_prepared_temp_app']);

Route::delete('/delete-temporal-licence/{slug}', [TemporalLicenceController::class,'destroy']);

Route::patch('/update-temp-licence', [TemporalLicenceController::class,'update'])->name('update_temp_licence');

Route::patch('/update-process-app-date/{slug}', [TemporalLicenceController::class,'updateDates']);

        Route::post('/submit-temporal-licence-document', [TemporalLicenceDocsController::class,'store']);

        Route::delete('/delete-temporal-licence-document/{id}', [TemporalLicenceDocsController::class,'destroy']);

        Route::post('/merge-temporal-documents/{type}', [TemporalLicenceDocsController::class,'merge']);

