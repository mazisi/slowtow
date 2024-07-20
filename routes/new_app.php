<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenceDocsController;
use App\Http\Controllers\NewApplicationController;


Route::get('/create-new-app',[NewApplicationController::class,'create'])->name('create_new_app');

Route::post('/submit-new-app',[NewApplicationController::class,'store'])->name('submit_new_app');

Route::patch('/update-new-app/{slug}',[NewApplicationController::class,'update'])->name('update_new_app');

Route::get('/new-application',[NewApplicationController::class,'view_registration'])->name('view_registration');



Route::post('/upload-licence-document',[LicenceDocsController::class,'store']);

Route::delete('/delete-licence-document/{id}/{prevStage}',[LicenceDocsController::class,'destroy'])->name('delete_licence_doc');

Route::patch('/update-new-registration/{slug}',[NewApplicationController::class,'updateRegistration']);

///Update status on check

Route::patch('/update-registration-date/{slug}',[NewApplicationController::class,'updateRegistrationDate']);

Route::post('/merge-licence-docs/{id}',[LicenceDocsController::class,'merge']);

