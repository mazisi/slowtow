<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Slowtowdmin\AdminsController;

Route::get('/slotow-admins',[AdminsController::class,'index'])->name('slotow_admins');

Route::post('/submit-user',[AdminsController::class,'store']);

Route::post('/update-user',[AdminsController::class,'update']);

Route::post('/deactivate-user/{id}/{status}',[AdminsController::class,'deactivate']);

Route::patch('/delete-user/{id}',[AdminsController::class,'destroy']);