<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyDocsController;
use App\Http\Controllers\Slowtowdmin\AddCompanyAdminController;

        Route::post('/add-company-admin',[AddCompanyAdminController::class,'store']);



        Route::get('/companies',[CompanyController::class,'index'])->name('companies');

        Route::get('/create-company',[CompanyController::class,'create'])->name('create_company');

        Route::get('/view-company/{slug}',[CompanyController::class,'show'])->name('view_company');

        Route::post('/submit-company',[CompanyController::class,'store'])->name('company.submit');

        Route::post('/update-company',[CompanyController::class,'update'])->name('company.update');

        Route::post('/submit-company-documents',[CompanyDocsController::class,'store']);

        Route::delete('/delete-company-document/{id}',[CompanyDocsController::class,'destroy']);

        Route::post('/add-people-to-company/{company_id}',[CompanyController::class,'attachPeopleToCompany']);

        Route::patch('/update-position/{id}',[CompanyController::class,'updatePeople']);

        Route::delete('/delete-company/{slug}',[CompanyController::class,'destroy']);

        Route::patch('/update-company-active-status/{slug}',[CompanyController::class,'updateActiveStatus']);



        Route::delete('/unlink-person/{id}',[CompanyController::class,'unlinkPerson'])->name('unlink_person');