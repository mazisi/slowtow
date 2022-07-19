<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyAdmin\CompanyController;
use App\Http\Controllers\CompanyAdmin\LicenceController;
use App\Http\Controllers\CompanyAdmin\DashboardController;
use App\Http\Controllers\CompanyAdmin\TemporalLicenceController;

Route::group(['middleware' => ['auth']], function () { 

  Route::group(['middleware' => ['role:company-admin']], function () {
      
      Route::prefix('company')->group(function () {

        Route::get('/dashboard',[DashboardController::class,'index'])->name('company_dashboard');
        Route::get('/licences',[LicenceController::class,'index'])->name('company_admin_licences');
        Route::get('/view-my-licences/{slug}',[LicenceController::class,'show'])->name('view_my_licences');

        Route::get('/my-companies',[CompanyController::class,'index'])->name('my_companies');
        Route::get('/view-my-company/{slug}',[CompanyController::class,'show'])->name('view_my_company');

        Route::get('/my-temp-licences',[TemporalLicenceController::class,'index'])->name('my_temp_licences');
    });

  });   
});