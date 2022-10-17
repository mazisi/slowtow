<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Email;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Exports\RenewalExport;
use App\Models\LicenceRenewal;
use App\Exports\TransferExports;

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
        
        $licenceTypes = LicenceType::pluck('licence_type', 'id');
        $companies = Company::pluck('name','id');        
        $people = People::pluck('full_name','id');
        $new_applications = Licence::with('licence_type')->where('is_new_app','1')->get();
        return Inertia::render('Report',[
             'licenceTypes' => $licenceTypes,
             'companies' => $companies,
             'people' => $people,
            'new_applications' => $new_applications]);
    }

    public function export(Request $request){
      switch ($request->variation) {
        case 'Renewals':
          RenewalExportController::export($request);          
          return Inertia::location("http://127.0.0.1:8000/force-download-renewal-export");
          break;
        case 'Transfers':
          TransferExportController::export($request);          
          return Inertia::location("http://localhost:8000/force-download-transfer-export");
          break;
        case 'Nominations':
          NominationExportController::export($request);          
          return Inertia::location("http://localhost:8000/force-download-nomination-export");
          break;
        
        default:
          echo 'Noo';
          break;
      }
    }

}
