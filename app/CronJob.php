<?php

use App\Http\Controllers\Reports\AllReportsController;
use App\Mail\ReportMailer;
use App\Models\Report;
use Illuminate\Support\Facades\Mail;

 function db_backup() : void {
  $report = Report::where('variation','All')->latest()->first();
if(!is_null($report)){
  AllReportsController::exportAll(request(), $report);
  Mail::to('info@goverify.co.za')->send(new ReportMailer($report));
   App\Models\Report::where('status','0')->update(['status' => '1']);
  }
}

db_backup();