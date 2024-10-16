<?php

namespace App\Http\Controllers\Reports;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Email;
use App\Models\People;
use App\Models\Report;
use App\Models\Company;
use App\Models\Licence;
use App\Mail\ReportMailer;
use App\Models\LicenceType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Reports\AllReportsController;
use App\Http\Controllers\Reports\NewAppExportController;
use App\Http\Controllers\Reports\RenewalExportController;
use App\Http\Controllers\Reports\TemporaLExportController;
use App\Http\Controllers\Reports\TransferExportController;
use App\Http\Controllers\Reports\AlterationExportController;
use App\Http\Controllers\Reports\NominationExportController;
use App\Http\Controllers\Reports\AllWholesaleReportController;
use App\Http\Controllers\Reports\ExistingLicenceExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleNewAppExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleRenewalExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleTransferExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleAlterationExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleExistingLicenceExportController;

class ReportController extends Controller
{
    public function index(Request $request){
      // if($request->type == 'wholesale'){
      //   return WholesaleReportController::index($request);
      // }
        $licenceTypes = null;
        $report_type = $request->type;
        $years = DB::table('years')->orderBy('year', 'DESC')->get()->pluck('year');
          $renewals = LicenceRenewal::with(['licence','renewal_documents' => function ($query){
              $query ->where('doc_type','Client Quoted');            
            }])->paginate(1);
  
            //Emails that aren t sent
            $emails = Email::with(['licence_renewals.licence' => function ($query){
              // $query ->where('doc_type','Client Quoted');            
            }])->where('model_type','Renewal')->get();

            if($request->type == 'wholesale'){
              $licenceTypes = [
                102 => "Distribution and Manufacturing Liquor Licence",
                103 => "Distribution Liquor Licence",
                104 => "Manufacturing Liquor Licence"
              ];

              }else{
                $licenceTypes = LicenceType::with('licence')->pluck('licence_type')->unique();
              }

          $companies = Company::pluck('name','id');        
          $people = People::pluck('full_name','id');
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
        })->where('is_new_app',1)->get();
        
          return Inertia::render('Reporting/Report',[
               'licenceTypes' => $licenceTypes,
               'companies' => $companies,
               'report_type' => $report_type,
               'people' => $people,
                'new_applications' => $new_applications,
               'years' => $years
          ]);
      }
  
      public function export(Request $request){
        switch ($request->variation) {
          case 'All':
            // Report::create([
            //   'variation' => $request->variation,
            //   'status' =>'0',
            //   'type' => $request->report_type,
            //   'email' => auth()->user()->email
            // ]);

            if($request->report_type == 'wholesale'){
              AllWholesaleReportsController::exportAll($request);
            }else{
              AllReportsController::exportAll($request);
            }
            return back()->with('success','Report is being generated. Please check your email');
            break;
            case 'Renewals':
              if($request->report_type == 'wholesale'){
                WholesaleRenewalExportController::export($request);
              }else{
                RenewalExportController::export($request);
              }
              break;            
              case 'Transfers':
                if($request->report_type == 'wholesale'){
                  WholesaleTransferExportController::export($request);
                }else{
                  TransferExportController::export($request);
                }
                      
            break;
          case 'Nominations':
            NominationExportController::export($request);          
            break;
          case 'New Applications':
            if($request->report_type == 'wholesale'){
              WholesaleNewAppExportController::export($request);
            }else{
              NewAppExportController::export($request);
            }
            break;
  
          case 'Existing Licences':
            if($request->report_type == 'wholesale'){
              WholesaleExistingLicenceExportController::export($request);
            }else{
              ExistingLicenceExportController::export($request);
            }
            break;
          case 'Temporary Licences':
            TemporaLExportController::export($request);          
            break;
  
            case 'Additional Depot/Relocation':
                WholesaleAlterationExportController::export($request);                        
              break; 
              
              case 'Alterations':
                  AlterationExportController::export($request);                          
                break; 
          case 'Upcoming Renewals':
            if($request->report_type == 'wholesale'){
              WholesaleRenewalExportController::export($request); 
            }else{
              RenewalExportController::export($request); 
            }
                      
            break;         
          
          default:
            return back()->with('error','Invalid selection');
            break;
        }
      }


 function dispatchCronjob(){//No longer working
$report = Report::where('variation','All')->latest()->first();
if(!is_null($report)){
  AllReportsController::exportAll(request(), $report);
  Mail::to('info@goverify.co.za')->send(new ReportMailer($report));
  Report::where('status','0')->update(['status' => '1']);
  }
}
}
