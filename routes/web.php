<?php

use Inertia\Inertia;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

use App\Http\Controllers\ViewFileController;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\InsertTypesController;

use App\Http\Controllers\MergeDocumentController;

use App\Http\Controllers\Reports\ReportController;

use App\Http\Controllers\SlotowDashboardController;

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AdditionalDocsController;


Route::get('/test',function(){
    return view('emails.ecomms.mail_base_template');
});

Route::get('/insert-licence-type',[InsertTypesController::class,'insert']);
Route::get('/insert-years',[InsertTypesController::class,'insert_years']);

Route::get('/slotow-admin-dashboard',[SlotowDashboardController::class,'index'])->name('slowtow_dashboard');

Route::get('/dashboard',[LoginController::class,'redirect_to_dash'])->name('dashboard');
Route::group(['middleware' => ['guest']], function () { 

 Route::get('/',function(){return Inertia::render('Auth/Login');})->name('home');

  Route::post('/login',[LoginController::class,'authenticate'])->name('login');

});


    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
    //Error Pages
    Route::get('server-error',function(){return Inertia::render('ErrorPages/ServerError');});
    Route::get('file-not-found',function(){return Inertia::render('ErrorPages/FileNotFound');});
    Route::get('access-denied',function(){return Inertia::render('ErrorPages/AccessDenied');});



    Route::group(['middleware' => ['auth', 'user_active','role:slowtow-admin|slowtow-user']], function () {

                //update password

        Route::get('/settings',[PasswordResetController::class,'index'])->name('settings');

        Route::post('/update-my-password',[PasswordResetController::class,'updatePassword'])->name('update_my_password');

        Route::group([], __DIR__.'/company.php');

        Route::group([], __DIR__.'/licence.php');

        Route::group([], __DIR__.'/wholesale.php');

        Route::group([], __DIR__.'/admin.php');
        //New Apps       
        Route::group([], __DIR__.'/new_app.php');
        // Get licence renewals.
        Route::group([], __DIR__.'/licence-variations/renewal.php');         

        Route::group([], __DIR__.'/licence-variations/transfer.php');
        
        Route::group([], __DIR__.'/alterations.php');

        Route::group([], __DIR__.'/contact.php');
        
        Route::group([], __DIR__.'/duplicate-originals.php');
        //Reports

        Route::get('/reports',[ReportController::class,'index'])->name('reports');

        Route::get('/export-report',[ReportController::class,'export'])->name('export');


        Route::group([], __DIR__.'/person.php');
       
        Route::group([], __DIR__.'/nomination.php');  
        
        Route::group([], __DIR__.'/temp_licence.php');

        Route::group([], __DIR__.'/email_comms.php');

      

          
        Route::get('view-file/{model}/{id}', [ViewFileController::class,'view_file'])->name('view_file');
        Route::post('/submit-task',[TaskController::class,'store'])->name('submit_task');
        Route::delete('/delete-task/{id}',[TaskController::class,'destroy'])->name('delete_task');

        //Additional Docs Stage
        Route::post('/store-additional-docs',[AdditionalDocsController::class,'store'])->name('submit_additional_doc');
        Route::delete('/delete-additional-doc/{id}',[AdditionalDocsController::class,'destroy'])->name('delete_additional_doc');
       
    });

    Route::group([], __DIR__.'/companyAdmin.php');

    Route::get('email-template',function(){
        return view('emails.mail-template');
    });

   
 
