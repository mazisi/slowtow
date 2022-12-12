<?php

namespace App\Http\Controllers;

use App\Exports\TemporalLicenceExports;
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
                $query->whereMonth('start_date', $request->month);
            })
            ->when(!empty(request('selectedDates')), function ($query) use ($request) {
                $query->whereIn('start_date',$request->selectedDates);
            })
            ->when(!empty(request('applicant')), function ($query) use ($request) {
                $query->where('belongs_to',$request->applicant);
            });
        })->get();
    // $licences = TemporalLicence::get();
    $notesCollection = null;
    $status = '';

    foreach ($licences as $licence) {
        $notes = Task::where('model_id',$licence->id)->where('model_type','Temporal Licence')->get();
   
        if(!is_null($notesCollection) || !empty($notesCollection)){
            foreach ($notes as $note) {
                $notesCollection .= ' || '. $note->body;
            }
        }
            switch ($licence->status) {
                case '1':
                   $status = 'Client Quoted';
                    break;
                case '2':
                    $status = 'Client Invoiced';
                    break;
                case '3':
                    $status = 'Client Paid';
                    break;
                case '4':
                    $status = 'Prepare Temporary Application';
                    break;
                case '5':
                    $status = 'Payment To The Liquor Board';
                    break;
                case '6':
                    $status = 'Scanned Application';
                    break;
                case '7':
                    $status = 'Temporary Licence Lodged ';
                    break;
                case '8':
                    $status = 'Temporary Licence Issued ';
                    break;
                case '9':
                    $status = 'Temporary Licence Delivered';
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
    $get_invoice_number = TemporalLicenceDocument::where('temporal_licence_id',$licence->id)->where('doc_type','Client Invoiced')->first();
    $licence_logded = TemporalLicenceDocument::where('temporal_licence_id',$licence->id)->where('doc_type','Licence Lodged')->first();

        TemporalLicenceExport::create([
            'event_name' => $licence->event_name,
            'applicant' => $applicant,
            'event_dates' => optional($licence->start_date)->format('d-m-Y'). ' - '.optional($licence->end_date)->format('d-m-Y'),
            // 'province' => $licence->address,
            'invoice_number' => is_null($get_invoice_number) ? '' : $get_invoice_number->document_name,
            'payment_date' => $licence->client_paid_at,
            'licence_number' => $licence->liquor_licence_number,
            'date_lodged' => optional($licence->logded_at)->format('d-m-Y'),
            'proof_of_lodgement' => is_null($licence_logded) ? 'FALSE': 'TRUE',
            'date_granted' => optional($licence->delivered_at)->format('d M Y'),
            'current_status' => $status,
            'notes' => $notesCollection
            
        ]);
    }
    
   
   
}

public function forceDownload(){
    return Excel::download(new TemporalLicenceExports(), 'temporal-licences.xlsx');
}
}
