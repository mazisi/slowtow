<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Licence;
use App\Models\NewAppExport;
use App\Exports\NewAppsExport;
use App\Models\LicenceDocument;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class NewAppExportController extends Controller
{
    public static function export($request){
        $exists = NewAppExport::get();                
        if(!is_null($exists)){
            foreach ($exists as $exist) {
                $exist->delete();
            }  
        }

        $licences = Licence::where(function($query) use($request){
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
            ->when(!empty(request('selectedDates')), function ($query) use ($request) {
               // $query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
            });
        })->get();

    $notesCollection = null;
    $status = '';

    foreach ($licences as $licence) {
        $notes = Task::where('model_id',$licence->id)->where('model_type','Licence')->get();
    //check if client has been logded
    $is_client_logded = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Application Lodged')->first();
        if(!is_null($notesCollection) || !empty($notesCollection)){
            foreach ($notes as $note) {
                $notesCollection += ' || '. $note;
            }
        }
          
            switch ($licence->status) {
                case '1':
                   $status = 'Client Quoted';
                    break;
                case '2':
                    $status = 'Deposit Paid';
                    break;
                case '3':
                    $status = 'Client Invoiced';
                    break;
                case '4':
                    $status = 'Prepare New Application';
                    break;
                case '5':
                    $status = 'Payment To The Liquor Board';
                    break;
                case '6':
                    $status = 'Scanned Application';
                    break;
                case '7':
                    $status = 'Application Lodged';
                    break;
                case '8':
                    $status = 'Initial Inspection';
                    break;
                case '9':
                    $status = 'Liquor Board Requests';
                    break;
                case '10':
                    $status = 'Final Inspection';
                    break;
                case '11':
                    $status = 'Activation Fee Requested';
                    break;
                case '12':
                    $status = 'Client Finalisation Invoice';
                    break;
                case '13':
                    $status = 'Client Paid';
                    break;
                case '14':
                    $status = 'Activation Fee Paid';
                    break;
                case '15':
                    $status = 'Licence Issued';
                    break;
                case '16':
                    $status = 'Licence Delivered';
                    break;
                default:
                    return back()->with('error','Could not process request.An unknown error occured');
                    break;
                }
        NewAppExport::create([
            'trading_name' => $licence->trading_name,
            'licence_number' => $licence->licence_number,
            'licence_type' => $licence->licence_type->licence_type,
            'province' => $licence->province,
            'deposit_invoice' => '',
            'deposit_paid' => is_null($licence->deposit_paid_at) ? 'FALSE': 'TRUE',
            'date_logded' => optional($licence->application_lodged_at)->format('d M Y'),
            'proof_of_lodgement' => is_null($is_client_logded) ? 'FALSE': 'TRUE',
            'activation_fee_paid' => $licence->activation_fee_paid_at,
            'final_invoice' => '',
            'full_payment' => optional($licence->client_paid_at)->format('d M Y'),
            'date_granted' => '',
            'current_status' => $status,
            'focus_for_the_week' => '',
            'notes' => $notesCollection
            
        ]);
    }
    
   
   
}

public function forceDownload(){
    return Excel::download(new NewAppsExport(), 'newApps.xlsx');
}
}