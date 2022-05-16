<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;

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

Route::get('/',function(){
    return Inertia::render('Auth/Login');
});

Route::get('/login',[AuthenticatedSessionController::class,'store'])->name('login');

Route::get('/companies',[CompanyController::class,'index'])->name('companies');
Route::get('/create-company',[CompanyController::class,'create'])->name('create_company');
Route::get('/view-company',[CompanyController::class,'show'])->name('view_company');
Route::post('/submit-company',[CompanyController::class,'store'])->name('company.submit');

Route::get('/tables',function(){
    return Inertia::render('Tables');
});


require __DIR__.'/auth.php';
