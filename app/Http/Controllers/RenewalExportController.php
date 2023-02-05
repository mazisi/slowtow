<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Exports\RenewalExport;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\DB;
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
                
            // $renewals = LicenceRenewal::with("licence")->when($request,function($query) use($request){
            //         $query->whereHas('licence', function($query) use($request){
            //             $query->when(request('month, function($query) use($request){
            //                 $query->whereMonth('licence_date', $request->month);
            //             })
                        
            //             ->when(request('province'), function ($query) use ($request) {
            //                 $query->whereIn('province',$request->province);
            //             })
            //             ->when(request('boardRegion'), function ($query) use ($request) {
            //                 $query->whereIn('board_region',$request->boardRegion);
            //             })
            //             ->when(request('applicant'), function ($query) use ($request) {
            //                 $query->where('belongs_to',$request->applicant);
            //             })
            //             ->when(request('licence_types'), function ($query) use ($request) {
            //                 $query->where('licence_type_id',$request->licence_types);
            //             });
            //         });
    
            //         })->when(request('selectedDates'), function ($query) use ($request) {
            //               $query->whereIn('year',$request->selectedDates);
            //         })->get();

                    $renewals = DB::table('licence_renewals')
                    ->selectRaw("licence_renewals.id, is_licence_active, trading_name, licence_number, licence_renewals.date, 
                                 licence_renewals.client_paid_at, payment_to_liquor_board_at, renewal_issued_at, renewal_delivered_at,
                                 is_quote_sent")

                    ->join('licences', 'licences.id' , '=', 'licence_renewals.licence_id')

                        ->when(function($query){
                            $query->when(request('month'), function($query){
                                $query->whereMonth('licence_date', request('month'));
                            })
                            ->when(request('province'), function ($query)  {
                                $query->whereIn('province',request('province'));
                            })
                            ->when(request('boardRegion'), function ($query)  {
                                $query->whereIn('board_region',request('boardRegion'));
                            })
                            ->when(request('applicant'), function ($query)  {
                                $query->where('belongs_to',request('applicant'));
                            })
                            ->when(request('licence_types'), function ($query)  {
                                $query->where('licence_type_id',request('licence_types'));
                            });

                        })->when(request('selectedDates'), function ($query) {
                              $query->whereIn('year',request('selectedDates'));
                        })
                    ->get();
        
            $notesCollection = ' ';

            

            foreach ($renewals as $renewal) {
                $notes = Task::where('model_id',$renewal->id)->where('model_type','Licence Renewal')->get(['body']);

            //check if client has been quoted
                   $is_quoted = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Quoted')->first();

                if(!is_null($notes) || !empty($notes)){
                    foreach ($notes as $note) {
                        $notesCollection .=  $note->body. ' ';
                    }
                }
                
                LicenceRenewalExports::create([
                    'licence_renewal_id' => $renewal->id,
                    'is_active' => ($renewal->is_licence_active) ? 'A' : 'D',
                    'trading_name' => $renewal->trading_name,
                    'licence_number' => $renewal->licence_number,
                    'renewal_date' => $renewal->date,
                    'is_quoted' => (is_null($is_quoted)) ? 'FALSE' : 'TRUE',
                    'is_quote_sent' => (is_null($renewal->is_quote_sent)) ? 'False' : 'True',
                    'payment_date' => $renewal->client_paid_at,
                    'invoice_number' => null,
                    'payment_to_liquour_board' => $renewal->payment_to_liquor_board_at,
                    'renewal_granted' => $renewal->renewal_issued_at,
                    'delivery_date' => $renewal->renewal_delivered_at,
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
