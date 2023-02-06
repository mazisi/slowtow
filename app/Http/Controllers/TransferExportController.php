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

                $transfers = DB::table('licence_transfers')
                    ->selectRaw("licence_transfers.id, is_licence_active, trading_name, licence_transfers.date, 
                                 licence_transfers.lodged_at, licence_transfers.status, payment_to_liquor_board_at, issued_at, delivered_at,province")

                    ->join('licences', 'licences.id' , '=', 'licence_transfers.licence_id')

                    ->when(function($query){
                        $query->when(request('month'), function($query){
                            $query->whereMonth('licence_date', request('month'));
                        })
                        ->when(request('province'), function ($query)  {
                            $query->whereIn('licences.province',request('province'));
                        })
                        ->when(request('boardRegion'), function ($query)  {
                            $query->whereIn('board_region',request('boardRegion'));
                        })
                        
                        ->when(request('applicant'), function ($query)  {
                            $query->where('belongs_to',request('applicant'));
                        })

                        ->when(request('activeStatus') === 'Inactive', function ($query) {
                            $query->where('is_licence_active',false);
                        })

                        ->when(request('activeStatus') == 'Active', function ($query) {
                            $query->where('is_licence_active', true);
                        })

                        ->when(request('licence_types'), function ($query)  {
                            $query->where('licence_type_id',request('licence_types'));
                        });

                    })->when(request('selectedDates'), function ($query) {
                          $query->whereIn('year',request('selectedDates'));
                    })
                    ->when(request('transfer_stages'), function ($query) {
                        $query->whereIn('licence_transfers.status', request('transfer_stages'));
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
                $notes = Task::where('model_id',$transfer->id)->where('model_type','Transfer')->get(['body']);
            //check if client has been quoted
           // $get_invoice_number = DB::table('transfer_documents')->where('licence_transfer_id',$transfer->id)->where('doc_type','Client Invoiced')->first(['document_name']);
                if(!is_null($notes) || !empty($notes)){
                    foreach ($notes as $note) {
                        $notesCollection .= '|| '. $note->body;
                    }
                }
                
                TransferExport::create([
                    'trading_name' => $transfer->trading_name,
                    'gau_or_blg_number' => '',
                    'province' => $transfer->province,
                    'deposit_invoice' => '',
                    'deposit_paid' => '',
                    'date_logded' => $transfer->lodged_at,
                    'proof_of_lodgement' => (is_null($transfer->lodged_at)) ? 'False' : 'True',
                    'payment_date' => $transfer->payment_to_liquor_board_at,
                    'invoice_number' => '',
                    'date_granted' => $transfer->issued_at,
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
