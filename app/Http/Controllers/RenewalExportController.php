<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Exports\RenewalExport;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LicenceRenewalExports;

class RenewalExportController extends Controller
{
    public function export(){
            $renewals = LicenceRenewal::with('licence')->get();
            $notesCollection = '';

            foreach ($renewals as $renewal) {
                $exist = LicenceRenewalExports::where('licence_renewal_id',$renewal->id)->first();
                
                if(!is_null($exist)){
                    continue;
                    // $exist->delete();
                }
                $notes = Task::where('model_id',$renewal->id)->where('model_type','Licence Renewal')->get();
            //check if client has been quoted
            $is_quoted = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Quoted')->first();
                if(!is_null($notesCollection) || !empty($notesCollection)){
                    foreach ($notes as $note) {
                        $notesCollection += ' '. $note;
                    }
                }
                LicenceRenewalExports::create([
                    'licence_renewal_id' => $renewal->id,
                    'is_active' => ($renewal->licence->is_licence_active) ? 'A' : 'D',
                    'trading_name' => $renewal->licence->trading_name,
                    'licence_number' => $renewal->licence->licence_number,
                    'renewal_date' => $renewal->date,
                    'is_quoted' => (is_null($is_quoted)) ? 'False' : 'True',
                    'is_quote_sent' => (is_null($renewal->is_quote_sent)) ? 'False' : 'True',
                    'payment_date' => $renewal->client_paid_at,
                    'invoice_number' => '',
                    'payment_to_liquour_board' => $renewal->payment_to_liquour_board_at,
                    'renewal_granted' => $renewal->renewal_issued_at,
                    'delivery_date' => $renewal->renewal_delivery_at,
                    'proof_of_delivery' => '???',
                    'notes' => $notesCollection
                ]);
            }
            return Excel::download(new RenewalExport, 'licence_renewals.xlsx');
           
           
    }

    

}