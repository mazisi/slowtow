<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlterLicenceController;
use App\Http\Controllers\AlterationDocumentController;


Route::get('/alterations',[AlterLicenceController::class,'index'])->name('alterations');

Route::get('/new-alteration',[AlterLicenceController::class,'newAlteration'])->name('new_alteration');

Route::post('/submit-altered-licence/{licence_id}',[AlterLicenceController::class,'store'])->name('alter_licence.submit');

Route::delete('/delete-altered-licence/{slug}/{licence_slug}',[AlterLicenceController::class,'destroy'])->name('delete_altered_licence.submit');

Route::get('/view-alteration/{slug}',[AlterLicenceController::class,'show'])->name('view_alteration');

Route::patch('/update-alteration',[AlterLicenceController::class,'update'])->name('update_alteration');

Route::post('/submit-alteration-document',[AlterationDocumentController::class,'store']);

Route::delete('/delete-alteration-document/{id}/{prevStage}',[AlterationDocumentController::class,'destroy']);

Route::post('/merge-alteration-documents/{alteration_id}',[AlterationDocumentController::class,'merge']);

Route::patch('/update-alteration-date/{slug}',[AlterLicenceController::class,'updateAlterationDate']);

Route::post('/abandon-alteration/{slug}',[AlterLicenceController::class,'abandon']);

