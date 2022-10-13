<?php

namespace App\Http\Controllers;

use App\Exports\RenewalExport;
use Inertia\Inertia;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceType;

class ReportController extends Controller
{
    public function index(){
        $renewals = LicenceRenewal::with(['licence','renewal_documents' => function ($query){
            $query ->where('doc_type','Client Quoted');            
          }])->paginate(1);

          //Emails that aren t sent
          $emails = Email::with(['licence_renewals.licence' => function ($query){
            // $query ->where('doc_type','Client Quoted');            
          }])->where('model_type','Renewal')->get();
        
        $licenceTypes = LicenceType::get(['licence_type','id']);
        return Inertia::render('Report',[
            'licenceTypes' => $licenceTypes]);
    }

    public function export(Request $request){
      switch ($request->variation) {
        case 'Renewals':
          RenewalExportController::export($request);          
          return Inertia::location("http://127.0.0.1:8000/force-download-export");
          break;
        
        default:
          echo 'Noo';
          break;
      }
    }

}
