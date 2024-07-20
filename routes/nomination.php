<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NominationController;
use App\Http\Controllers\MergeDocumentController;

Route::get('/nominate',[NominationController::class,'index'])->name('nominate');

Route::post('/submit-nomination', [NominationController::class,'store'])->name('submit_nomination');

Route::get('/nominations',[NominationController::class,'index'])->name('nominations');

Route::get('/view-nomination/{slug}',[NominationController::class,'viewIndividualNomination'])->name('view_nomination');

Route::post('/terminate-person/{id}/{slug}', [NominationController::class,'terminate'])->name('terminate_person');

Route::patch('/update-nominee',[NominationController::class,'update'])->name('update_nominee');

Route::post('/add-selected-nominees',[NominationController::class,'addSelectedNominees']);

Route::post('/detach-nominee/{nomination_id}/{nominee_id}',[NominationController::class,'detachNominee']);

Route::post('/submit-nomination-document',[NominationController::class,'uploadDocument']);

Route::delete('/delete-nomination-document/{id}/{prevStage}',[NominationController::class,'deleteDocument']);

Route::delete('/delete-nomination/{licence_slug}/{slug}',[NominationController::class,'destroy']);

Route::patch('/update-nomination-date/{slug}',[NominationController::class,'updateDate']);

Route::get('/merge-document/{id}',[MergeDocumentController::class,'merge'])->name('merge');      