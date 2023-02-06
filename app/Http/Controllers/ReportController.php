<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Email;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\RenewalExport;
use App\Models\LicenceRenewal;
use App\Exports\TransferExports;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TemporaLExportController;
use App\Http\Controllers\NominationExportController;
use App\Http\Controllers\AlterationExportController;

class ReportController extends Controller
{
    public function index(Request $request){
      $years = DB::table('years')->get()->pluck('year');
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
        // $new_applications = Licence::with('licence_type')->where('is_new_app','1')->get();
        $sortedStatus = Arr::sort($request->new_app_stages);

        $new_applications = Licence::with('licence_type')->where(function($query) use($request,$sortedStatus){
          $query->when($request->month, function($query) use($request){
              $query->whereIn(DB::raw('MONTH(licence_date)'), $request->month);
          })
          ->when(!empty(request('activeStatus')), function ($query) use ($request) {
              $query->where('is_licence_active',$request->activeStatus);
          })
          ->when(!empty(request('province')), function ($query) use ($request) {
              $query->whereIn('province',$request->province);
          })
          ->when(!empty(request('boardRegion')), function ($query) use ($request) {
              $query->whereIn('board_region',$request->boardRegion);
          })
          ->when(!empty(request('applicant')), function ($query) use ($request) {
              $query->where('belongs_to',$request->applicant);
          })
          ->when(!empty(request('licence_types')), function ($query) use ($request) {
              $query->where('licence_type_id',$request->licence_types);
          })
          ->when(!empty(request('new_app_stages')), function ($query) use ($sortedStatus) {
            $query->where('status','<=', last($sortedStatus));
        })
          ->when(!empty(request('selectedDates')), function ($query) use ($request) {
             // $query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
          });
      })->where('is_new_app',true)->get();
      
        return Inertia::render('Report',[
             'licenceTypes' => $licenceTypes,
             'companies' => $companies,
             'people' => $people,
            'new_applications' => $new_applications,
             'years' => $years
        ]);
    }

    public function export(Request $request){
      switch ($request->variation) {
        case 'Renewals':
          RenewalExportController::export($request);          
          return Inertia::location(env('APP_URL').'/force-download-renewal-export');
          break;
        case 'Transfers':
          TransferExportController::export($request);          
          return Inertia::location(env('APP_URL').'/force-download-transfer-export');
          break;
        case 'Nominations':
          NominationExportController::export($request);          
          return Inertia::location(env('APP_URL').'/force-download-nomination-export');
          break;
        case 'Registrations':
          NewAppExportController::export($request);          
           return Inertia::location(env('APP_URL').'/force-download-new-app-export');
          break;

        case 'Existing-Licences':
          ExistingLicenceExportController::export($request);          
            return Inertia::location(env('APP_URL').'/force-download-new-app-export');
          break;
        case 'Temporal Licence':
          TemporaLExportController::export($request);          
            return Inertia::location(env('APP_URL').'/force-download-temp-licence-export');
          break;

          case 'Alterations':dd('Waiting For Data');
            AlterationExportController::export($request);          
              return Inertia::location(env('APP_URL').'/force-download-temp-licence-export');
            break;         
        
        default:
          return back()->with('error','Invalid selection');
          break;
      }
    }

}
