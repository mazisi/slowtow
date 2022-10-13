<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Exports\RenewalExport;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LicenceRenewalExports;

class RenewalExportController extends Controller
{
    public static function export($request){
        $selectedDates = $request->selectedDates;
      

            $renewals = LicenceRenewal::with("licence")->when(function($query) use($request){
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

                })->when(!empty(request('year')), function ($query) use ($request) {
                    $query->whereIn('year',$request->year);
                })->get();
              
            $notesCollection = '';

            foreach ($renewals as $renewal) {
                $exist = LicenceRenewalExports::where('licence_renewal_id',$renewal->id)->first();
                
                if(!is_null($exist)){
                    //$exist->delete();
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
