<?php

namespace App\Http\Controllers;

use App\Exports\TransferExports;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\TransferExport;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransferExportController extends Controller
{
public static function export($request){
    
            $exists = TransferExport::get();
                          
                if(!is_null($exists)){
                    foreach ($exists as $exist) {
                        $exist->delete();
                    }  
                    
                }

            $transfers = LicenceTransfer::with("licence")->when(function($query) use($request){
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
                    })
                    ->when(!empty(request('licence_types')), function ($query) use ($request) {
                        $query->whereIn('licence_type_id',$request->licence_types);
                    });
                });

                })->when(!empty(request('selectedDates')), function ($query) use ($request) {
                      $query->whereIn('year',$request->selectedDates);
                })->get();
            $status = '';
            $notesCollection = '';


            foreach ($transfers as $transfer) {
                switch ($transfer->status) {
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
                        $status = 'Prepare Transfer Application';
                        break;
                    case '5':
                        $status = 'Payment To The Liquor Board';
                        break;
                    case '6':
                        $status = 'Scanned Application';
                        break;
                    case '7':
                        $status = 'Application Logded';
                        break;
                    case '8':
                        $status = 'Activation Fee Paid';
                        break;
                    case '9':
                        $status = 'Transfer Issued';
                        break;
                    case '10':
                        $status = 'Transfer Delivered';
                        break;
                    default:
                        return back()->with('error','Could not process request.An unknown error occured');
                        break;
                }
                $notes = Task::where('model_id',$transfer->id)->where('model_type','Transfer')->get();
            //check if client has been quoted
            $is_client_paid = TransferDocument::where('licence_transfer_id',$transfer->id)->where('doc_type','Payment To The Liquor Board')->first();
                if(!is_null($notesCollection) || !empty($notesCollection)){
                    foreach ($notes as $note) {
                        $notesCollection += '|| '. $note;
                    }
                }
                TransferExport::create([
                    'trading_name' => $transfer->licence->trading_name,
                    'gau_or_blg_number' => '',
                    'province' => $transfer->licence->province,
                    'deposit_invoice' => '',
                    'deposit_paid' => '',
                    'date_logded' => $transfer->lodged_at,
                    'proof_of_lodgement' => (is_null($transfer->lodged_at)) ? 'False' : 'True',
                    'payment_date' => $transfer->payment_to_liquor_board_at,
                    'invoice_number' => null,
                    'date_granted' => $transfer->renewal_issued_at,
                    'finalisation_invoice' => '',
                    'finalisation_payment' => '',
                    'current_status' => $status,
                    'notes' => $notesCollection
                ]);
            }
}


public function forceDownload() {
        return Excel::download(new TransferExports(), 'transfers.xlsx');
    }
}
