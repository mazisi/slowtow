<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/goverify-contacts',[ContactController::class,'index'])->name('contacts');

Route::get('/upload-contacts',[ContactController::class,'create'])->name('upload_contacts');

Route::post('/store-individual-contact',[ContactController::class,'storeIndividualContact'])->name('store_individual_contact');

Route::patch('/update-individual-contact/{id}',[ContactController::class,'updateIndividualContact'])->name('update_individual_contact');

Route::delete('/delete-individual-contact/{id}',[ContactController::class,'destroy'])->name('delete_individual_contact');

Route::get('/create-contact',[ContactController::class,'createContact'])->name('create_contacts');

Route::get('/view-contact/{id}',[ContactController::class,'viewContact'])->name('view_contact');

Route::post('/submit-contacts',[ContactController::class,'store'])->name('submit_contacts');

Route::delete('/delete-contact/{id}',[ContactController::class,'destroy'])->name('delete_contact');