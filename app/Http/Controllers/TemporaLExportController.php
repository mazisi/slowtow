<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TemporalLicence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TemporalLicenceExport;
use App\Models\TemporalLicenceDocument;

class TemporaLExportController extends Controller
{
    public static function export($request){
        $exists = TemporalLicenceExport::get();                
        if(!is_null($exists)){
            foreach ($exists as $exist) {
                $exist->delete();
            }  
        }

        $licences = TemporalLicence::where(function($query) use($request){
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

                $applicant = '';
                switch ($licence->belongs_to) {
                    case 'Company':
                        $applicant = $licence->company->name;
                        break;
                    case 'Person':
                        $applicant = $licence->people->full_name;
                        break;
                    
                    default:
                        //
                        break;
                }
                //get invoice number
    $get_invoice_number = TemporalLicenceDocument::where('temporal_licence_id',$licence->id)->where('document_type','CLient Invoiced')->first();
    $licence_logded = TemporalLicenceDocument::where('temporal_licence_id',$licence->id)->where('document_type','Licence Lodged')->first();
    
        TemporalLicenceExport::create([
            'event_name' => $licence->event_name,
            'applicant' => $applicant,
            'event_dates' => optional($licence->start_date)->format('d-m-Y'). ' - '.optional($licence->end_date)->format('d-m-Y'),
            'province' => $licence->address,
            'invoice_number' => $get_invoice_number->document_name,
            'payment_date' => $licence->client_paid_at,
            'licence_number' => $licence->liquor_licence_number,
            'date_lodged' => optional($licence->logded_at)->format('d-m-Y'),
            'proof_of_lodgement' => is_null($licence_logded) ? 'FALSE': 'TRUE',
            'date_granted' => optional($licence->delivered_at)->format('d M Y'),
            'notes' => $notesCollection
            
        ]);
    }
    
   
   
}

public function forceDownload(){
    return Excel::download(new TemporalLicenceExport(), 'temporal-licences.xlsx');
}
}
