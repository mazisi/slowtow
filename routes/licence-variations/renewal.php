<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenceRenewalController;

Route::get('/renew-licence',[LicenceRenewalController::class,'renewLicence'])->name('renew_licence');

Route::post('/submit-licence-renewal',[LicenceRenewalController::class,'store'])->name('renew_licence.submit');

Route::get('/view-licence-renewal/{slug}',[LicenceRenewalController::class,'show'])->name('view_licence_renewal');

Route::patch('/update-renewal',[LicenceRenewalController::class,'update'])->name('update_licence_renewal');

Route::post('/submit-renewal-documents',[LicenceRenewalController::class,'uploadDocuments']);

Route::delete('/delete-renewal-document/{id}/{prevStage}',[LicenceRenewalController::class,'deleteDocument']);

Route::patch('/update-renewal-date/{slug}',[LicenceRenewalController::class,'updateDates']);

Route::delete('/delete-licence-renewal/{licence_slug}/{slug}',[LicenceRenewalController::class,'destroy'])->name('delete_licence_renewal');


//wholesale variation routes

Route::get('/wholesale-renewal/{slug}',[LicenceRenewalController::class,'wholesaleRenewal'])->name('wholesale_renewal');

Route::post('/submit-turn-over-information/{id}',[LicenceRenewalController::class,'submitTurnOverInformation'])->name('submit_turn_over_information');
