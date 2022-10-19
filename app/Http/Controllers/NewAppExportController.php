<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Models\NewAppExport;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use Illuminate\Support\Facades\DB;

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

    $notesCollection = '';

    foreach ($licences as $licence) {
        $notes = Task::where('model_id',$licence->id)->where('model_type','Licence')->get();
    //check if client has been quoted
    $is_quoted = LicenceDocument::where('licence_id',$licence->id)->where('doc_type','Client Quoted')->first();
        if(!is_null($notesCollection) || !empty($notesCollection)){
            foreach ($notes as $note) {
                $notesCollection += ' '. $note;
            }
        }
        NewAppExport::create([
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

public function forceDownload(){
    return Excel::download(new NewAppExport(), 'newApps.xlsx');
}
}
