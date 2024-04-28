<?php

// use App\Http\Controllers\Reports\AllReportsController;
// use App\Mail\ReportMailer;
// use App\Models\Report;
// use Illuminate\Support\Facades\Mail;

 function db_backup() : void {
  $report = App\Models\Report::where('variation','All')->latest()->first();
if(!is_null($report)){
    App\Models\User::create([
        'email' => 'test@example.com',
        'password' => '12345',
        'name' => 'Msebele M'
        ]);
  App\Http\Controllers\Reports\AllReportsController::exportAll(request(), $report);
  Illuminate\Support\Facades\Mail::to('info@goverify.co.za')->send(new App\Mail\ReportMailer($report));
   App\Models\Report::where('status','0')->update(['status' => '1']);
  }
}

db_backup();