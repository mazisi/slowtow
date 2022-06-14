<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmailCommsController;
use App\Http\Controllers\NominationController;
use App\Http\Controllers\InsertTypesController;
use App\Http\Controllers\LicenceDocsController;
use App\Http\Controllers\AlterLicenceController;
use App\Http\Controllers\LicenceRenewalController;
use App\Http\Controllers\TemporalLicenceController;
use App\Http\Controllers\TransferLicenceController;
use App\Http\Controllers\Auth\PasswordResetController;
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
Route::get('/insert-licence-type',[InsertTypesController::class,'insert']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::group(['middleware' => ['guest']], function () { 
 Route::get('/',function(){return Inertia::render('Auth/Login');})->name('home');

  Route::post('/login',[LoginController::class,'authenticate'])->name('login');
});

Route::group(['middleware' => ['auth']], function () { 
        Route::get('/logout',[LoginController::class,'logout'])->name('logout');
        //update password
        Route::get('/settings',[PasswordResetController::class,'index'])->name('settings');
        Route::post('/update-my-password',[PasswordResetController::class,'updatePassword'])->name('update_my_password');

        
        Route::post('/add-company-admin',[AddCompanyAdminController::class,'store']);

        Route::get('/companies',[CompanyController::class,'index'])->name('companies');
        Route::get('/create-company',[CompanyController::class,'create'])->name('create_company');
        Route::get('/view-company/{slug}',[CompanyController::class,'show'])->name('view_company');
        Route::post('/submit-company',[CompanyController::class,'store'])->name('company.submit');
        Route::post('/update-company',[CompanyController::class,'update'])->name('company.update');

        Route::get('/licences',[LicenceController::class,'index'])->name('licences');
        Route::get('/create-licence',[LicenceController::class,'create'])->name('create_licence');
        Route::get('/view-licence',[LicenceController::class,'show'])->name('view_licence');
        Route::post('/submit-licence',[LicenceController::class,'store'])->name('submit_licence');
        Route::post('/update-licence/{slug}',[LicenceController::class,'update'])->name('update_licence');
        Route::post('/upload-licence-document',[LicenceDocsController::class,'store'])->name('submit_licence_doc');
        Route::delete('/delete-licence/{slug}',[LicenceController::class,'destroy'])->name('delete_licence');

      
         // Get licence renewals.
        Route::get('/renew-licence',[LicenceRenewalController::class,'renewLicence'])->name('renew_licence');

        //renew licence.submit
        Route::post('/submit-licence-renewal',[LicenceRenewalController::class,'store'])->name('renew_licence.submit');
        Route::get('/view-temp-licence/{slug}',[LicenceRenewalController::class,'viewLicence'])->name('view_temp_licence');
        Route::delete('/delete-licence-renewal/{slug}',[LicenceRenewalController::class,'destroy'])->name('delete_licence_renewal');
        

        Route::get('/transfer-licence',[TransferLicenceController::class,'index'])->name('transfer_licence');
        Route::post('/transfer-licence-submit/{slug}',[TransferLicenceController::class,'store'])->name('transfer_licence.submit');
        Route::get('/transfer-history',[TransferLicenceController::class,'transferHistory'])->name('transfer_history');
        Route::get('/view-transfered-licence/{slug}',[TransferLicenceController::class,'viewTransferedLicence'])->name('view-transfered_licence');
        Route::delete('/delete-licence-transfer/{slug}/{licence_slug}',[TransferLicenceController::class,'destroy'])->name('delete_licence_transfer');
        Route::patch('/update-licence-transfer',[TransferLicenceController::class,'update'])->name('update_licence_transfer');

        Route::get('/alterations',[AlterLicenceController::class,'index'])->name('alterations');
        Route::get('/new-alteration',[AlterLicenceController::class,'newAlteration'])->name('new_alteration');
        Route::post('/submit-altered-licence/{licence_id}',[AlterLicenceController::class,'store'])->name('alter_licence.submit');
        Route::delete('/delete-altered-licence/{slug}',[AlterLicenceController::class,'destroy'])->name('delete_altered_licence.submit');
        
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
        Route::post('/delete-person/{slug}', [PersonController::class,'destroy'])->name('delete_person');
       

        Route::get('/nominate',[NominationController::class,'index'])->name('nominate');
        Route::post('/submit-nomination', [NominationController::class,'store'])->name('submit_nomination');
        Route::get('/nominations',[NominationController::class,'nominations'])->name('nominations');
        Route::get('/view-nomination/{slug}',[NominationController::class,'viewIndividualNomination'])->name('view_nomination');
        Route::post('/terminate-person/{id}/{slug}', [NominationController::class,'terminate'])->name('terminate_person');
        Route::post('/update-nominee',[NominationController::class,'update'])->name('update_nominee');

        Route::get('/temp-licences', [TemporalLicenceController::class,'index'])->name('temp_licences');
        Route::get('/create-temp-licence', [TemporalLicenceController::class,'create'])->name('create_temp_licence');
        Route::post('/submit-temp-licence', [TemporalLicenceController::class,'store'])->name('store_temp_licence');
        Route::get('/view-temp-licence/{slug}', [TemporalLicenceController::class,'show'])->name('view_temp_licence');
        Route::post('/update-temp-licence/{slug}', [TemporalLicenceController::class,'update'])->name('update_temp_licence');
        Route::post('/delete-temp-licence/{slug}', [TemporalLicenceController::class,'destroy'])->name('update_temp_licence');
        

        Route::post('/submit-task',[TaskController::class,'store'])->name('submit_task');
        Route::delete('/delete-task/{id}',[TaskController::class,'destroy'])->name('delete_task');

        Route::get('/email-comms', [EmailCommsController::class,'index'])->name('email_comms');
        Route::get('/email-comms/transfers', [EmailCommsController::class,'getLicenceTransfers'])->name('get_licence_transfers');
        Route::get('/email-comms/send-mail/{slug}/{licence_variation}', [EmailCommsController::class,'processMail'])->name('send_mail');

    });
