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
    public static function export($request){
                $exists = LicenceRenewalExports::get();                
                if(!is_null($exists)){
                    foreach ($exists as $exist) {
                        $exist->delete();
                    }  
                }
                
            $renewals = LicenceRenewal::with("licence")->when($request,function($query) use($request){
                    $query->whereHas('licence', function($query) use($request){
                        $query->when($request->month, function($query) use($request){
                            $query->whereMonth('licence_date', $request->month);
                        })
                        ->when(request('activeStatus') == 'Active', function ($query) {
                            $query->whereNotNull('is_licence_active');
                        })
                        ->when(request('activeStatus') == 'Inactive', function ($query) {
                            $query->whereNull('is_licence_active');
                        })
                        ->when(request('province'), function ($query) use ($request) {
                            $query->whereIn('province',$request->province);
                        })
                        ->when(request('boardRegion'), function ($query) use ($request) {
                            $query->whereIn('board_region',$request->boardRegion);
                        })
                        ->when(request('applicant'), function ($query) use ($request) {
                            $query->where('belongs_to',$request->applicant);
                        })
                        ->when(request('licence_types'), function ($query) use ($request) {
                            $query->where('licence_type_id',$request->licence_types);
                        });
                    });
    
                    })->when(request('selectedDates'), function ($query) use ($request) {
                          $query->whereIn('year',$request->selectedDates);
                    })->get();
        
            $notesCollection = '';

            foreach ($renewals as $renewal) {
                $notes = Task::where('model_id',$renewal->id)->where('model_type','Licence Renewal')->get();
            //check if client has been quoted
            $is_quoted = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Quoted')->first();
                if(!is_null($notesCollection) || !empty($notesCollection)){
                    foreach ($notes as $note) {
                        $notesCollection .= ' '. $note->body;
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
                    'invoice_number' => null,
                    'payment_to_liquour_board' => $renewal->payment_to_liqour_board_at,
                    'renewal_granted' => $renewal->renewal_issued_at,
                    'delivery_date' => $renewal->renewal_delivery_at,
                    'proof_of_delivery' => null,
                    'notes' => $notesCollection
                ]);
            }
            
           
           
    }

    public function forceDownload()
    {
        return Excel::download(new RenewalExport, 'renewals.xlsx');
    }

}
