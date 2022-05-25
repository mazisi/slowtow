<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NominationController;
use App\Http\Controllers\LicenceDocsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Slowtowdmin\AddCompanyAdminController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::group(['middleware' => ['guest']], function () { 
 Route::get('/',function(){return Inertia::render('Auth/Login');})->name('home');

  Route::post('/login',[LoginController::class,'authenticate'])->name('login');
});

Route::group(['middleware' => ['auth']], function () { 
        Route::get('/logout',[LoginController::class,'logout'])->name('logout');

        Route::post('/add-company-admin',[AddCompanyAdminController::class,'store']);

        Route::get('/companies',[CompanyController::class,'index'])->name('companies');
        Route::get('/create-company',[CompanyController::class,'create'])->name('create_company');
        Route::get('/view-company/{slug}',[CompanyController::class,'show'])->name('view_company');
        Route::post('/submit-company',[CompanyController::class,'store'])->name('company.submit');
        Route::post('/update-company',[CompanyController::class,'update'])->name('company.update');

        Route::get('/licences',[LicenceController::class,'index'])->name('licences');
        Route::get('/create-licence/{slug}',[LicenceController::class,'create'])->name('create_licence');
        Route::get('/view-licence/{slug}',[LicenceController::class,'show'])->name('view_licence');
        Route::post('/submit-licence/{id}',[LicenceController::class,'store'])->name('submit_licence');
        Route::post('/update-licence/{slug}',[LicenceController::class,'update'])->name('update_licence');
        Route::post('/upload-licence-document',[LicenceDocsController::class,'store'])->name('submit_licence_doc');

        Route::get('/goverify-contacts',[ContactController::class,'index'])->name('contacts');
        Route::get('/upload-contacts',[ContactController::class,'create'])->name('upload_contacts');
        Route::post('/submit-contacts',[ContactController::class,'store'])->name('submit_contacts');
        Route::get('/delete-contact/{id}',[ContactController::class,'destroy'])->name('delete_contact');

        Route::get('/reports',[ReportController::class,'index'])->name('reports');

        
        Route::get('/people', [PersonController::class,'index'])->name('people');
        Route::get('/create-person', [PersonController::class,'create'])->name('create_person');
        Route::post('/submit-person', [PersonController::class,'store'])->name('submit_person');
        Route::get('/view-person/{slug}', [PersonController::class,'show'])->name('view_person');
        Route::post('/update-person', [PersonController::class,'update'])->name('update_person');

        Route::get('/nominate/{slug}',[NominationController::class,'index'])->name('nominate');
        Route::post('/submit-nomination', [NominationController::class,'store'])->name('submit_nomination');
        

    });
