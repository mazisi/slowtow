<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailCommsController;
use App\Http\Controllers\EmailReportController;
use App\Actions\EmmailCommsHandlers\HandleNewAppMail;
use App\Actions\EmmailCommsHandlers\HandleRenewalMail;
use App\Actions\EmmailCommsHandlers\HandleTransferMail;
use App\Http\Controllers\NominationEmailCommsController;
use App\Actions\EmmailCommsHandlers\HandleAlterationMail;
use App\Actions\EmmailCommsHandlers\HandleNominationMail;
use App\Actions\EmmailCommsHandlers\HandleTemporalLicenceMail;
use App\Http\Controllers\EmailComms\NewAppEmailCommsController;
use App\Http\Controllers\EmailComms\TransferEmailCommsController;
use App\Http\Controllers\EmailComms\AlterationEmailCommsController;
use App\Http\Controllers\EmailComms\TemporaryLicenceEmailCommsController;

Route::get('/email-comms', [EmailCommsController::class,'index'])->name('email_comms');

Route::get('/email-comms/transfers', [TransferEmailCommsController::class,'getLicenceTransfers'])->name('get_licence_transfers');

Route::get('/email-comms/nominations', [NominationEmailCommsController::class,'getNominations'])->name('get_nominations');

Route::get('/email-comms/alterations', [AlterationEmailCommsController::class,'getAlterationTemplate'])->name('get_alterations');

Route::get('/email-comms/get-mail-template/{slug}/{licence_variation}', [EmailCommsController::class,'getMailTemplate']);

Route::get('/email-comms/temp-licences', [TemporaryLicenceEmailCommsController::class,'getTemporaryLicenceTemplate'])->name('get_temp_licences');

Route::post('email-comms/filter-by-month', [EmailCommsController::class,'index']);

Route::get('/email-comms/new-apps', [NewAppEmailCommsController::class,'getNewAppTemplate'])->name('get_new_app_template');



//Emmails Not sent 

Route::get('emails-report', [EmailReportController::class,'index']);

Route::post('/dispatch-alteration-mail', [HandleAlterationMail::class,'dispatchAlterationMail'])->name('dispatch_alteration_mail');

Route::post('/dispatch-temporal-licence-mail', [HandleTemporalLicenceMail::class,'dispatchTemporalMail'])->name('dispatch_temporal_mail');

Route::post('/dispatch-new-app-mail', [HandleNewAppMail::class,'dispatchNewAppMail'])->name('dispatch_new_app_mail');

//renewals mail dispatcher

Route::post('/dispatchRenewalMail', [HandleRenewalMail::class,'dispatchRenewalMail'])->name('dispatch_renewal_mail');

//transfers mail dispatcher

Route::post('/dispatchTransferMail', [HandleTransferMail::class,'dispatchTransferMail'])->name('dispatch_transfer_mail');

//nomination mail dispatcher

Route::post('/dispatchNominationMail', [HandleNominationMail::class,'dispatchNominationMail'])->name('dispatch_nom_mail');