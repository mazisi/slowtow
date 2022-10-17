<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\NominationExport;
use App\Exports\NominationExports;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class NominationExportController extends Controller
{
    public static function export($request){
    
        $exists = NominationExport::get();
                      
            if(!is_null($exists)){
                foreach ($exists as $exist) {
                    $exist->delete();
                }                  
            }

        $nominations = Nomination::with("licence")->when(function($query) use($request){
            $query->whereHas('licence', function($query) use($request){
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
                });
            });

            })->when(!empty(request('selectedDates')), function ($query) use ($request) {
                  $query->whereIn('year',$request->selectedDates);
            })->get();
        $status = '';
        $notesCollection = '';
        $i = 0;
        foreach ($nominations as $nom) {
            switch ($nom->status) {
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
                    $status = 'Payment To The Liquor Board';
                    break;
                case '5':
                    $status = 'Select Nominees';
                    break;
                case '6':
                    $status = 'Prepare Nomination Application';
                    break;
                case '7':
                    $status = 'Scanned Application';
                    break;
                case '8':
                    $status = 'Nomination Lodged';
                    break;
                case '9':
                    $status = 'Nomination Issued';
                    break;
                case '10':
                    $status = 'Nomination Delivered';
                    break;
                default:
                    //
                    break;
            }
            $notes = Task::where('model_id',$nom->id)->where('model_type','Nomination')->get();

        $is_client_paid = NominationDocument::where('nomination_id',$nom->id)->where('doc_type','Payment To The Liquor Board')->first();
            if(!is_null($notesCollection) || !empty($notesCollection)){
                foreach ($notes as $note) {
                    $notesCollection += '|| '. $note;
                }
            }
            NominationExport::create([
                'trading_name' => $nom->licence->trading_name,
                'client_name' => $nom->people[$i]->full_name,
                'licence_number' => $nom->licence->licence_number,
                'province' => $nom->licence->province,
                'invoice_number' => null,
                'payment_date' => $nom->payment_to_liquor_board_at,
                'date_logded' => $nom->nomination_lodged_at,
                'proof_of_lodgement' => (is_null($nom->nomination_lodged_at)) ? 'False' : 'True',
                'date_granted' => '',
                'current_status' => $status,                
                'notes' => $notesCollection
            ]);
        }
}


public function forceDownload() {
    return Excel::download(new NominationExports(), 'nominations.xlsx');
}
}
