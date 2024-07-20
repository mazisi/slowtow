<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferDocsController;
use App\Http\Controllers\TransferLicenceController;

Route::get('/transfer-licence',[TransferLicenceController::class,'index'])->name('transfer_licence');

Route::post('/transfer-licence-submit/{slug}',[TransferLicenceController::class,'store'])->name('transfer_licence.submit');

Route::get('/transfer-history',[TransferLicenceController::class,'transferHistory'])->name('transfer_history');

Route::get('/view-transfered-licence/{slug}',[TransferLicenceController::class,'viewTransferedLicence'])->name('view_transfered_licence');

Route::delete('/delete-licence-transfer/{slug}/{licence_slug}',[TransferLicenceController::class,'destroy'])->name('delete_licence_transfer');

Route::patch('/update-licence-transfer',[TransferLicenceController::class,'update'])->name('update_licence_transfer');

Route::patch('/update-transfer-date/{slug}',[TransferLicenceController::class,'updateDates']);

Route::post('/submit-transfer-documents/{transfer_id}',[TransferDocsController::class,'store'])->name('transfer_licence_docs');

        Route::post('/transfer-documents-merge',[TransferDocsController::class,'merge']);

        Route::delete('/delete-transfer-document/{document_id}/{prevStage}',[TransferDocsController::class,'destroy']);
