<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PeopleDocumentController;

Route::get('/people', [PersonController::class,'index'])->name('people');

Route::get('/create-person', [PersonController::class,'create'])->name('create_person');

Route::post('/submit-person', [PersonController::class,'store'])->name('submit_person');

Route::get('/view-person/{slug}', [PersonController::class,'show'])->name('view_person');

Route::post('/update-person', [PersonController::class,'update'])->name('update_person');

Route::delete('/delete-person/{slug}', [PersonController::class,'destroy'])->name('delete_person');

Route::post('/upload-person-documents', [PeopleDocumentController::class,'uploadDocument']);

Route::delete('/delete-person-document/{slug}', [PeopleDocumentController::class,'deleteDocument']);

Route::patch('/update-person-active-status/{slug}',[PersonController::class,'updateActiveStatus']);