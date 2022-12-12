<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Alteration;
use App\Models\AlterationExport;
use App\Exports\AlterationExports;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AlterationExportController extends Controller
{
    public static function export($request){
        $exists = AlterationExport::get();                
        if(!is_null($exists)){
            foreach ($exists as $exist) {
                $exist->delete();
            }  
        }

    $alterations = Alteration::with("licence")->when(function($query) use($request){
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
            });
        });

        })->when(!empty(request('selectedDates')), function ($query) use ($request) {
              $query->whereIn(DB::raw('MONTH(date)'), $request->month);
        })->get();
      
    $notesCollection = '';

    foreach ($alterations as $alteration) {
        $notes = Task::where('model_id',$alteration->id)->where('model_type','Alteration')->get();
    
        if(!is_null($notesCollection) || !empty($notesCollection)){
            foreach ($notes as $note) {
                $notesCollection += ' '. $note;
            }
        }
        AlterationExport::create([
            // 'trading_name' => $alteration->licence->trading_name,
            // 'licence_number' => $alteration->licence->licence_number,
            // 'province' => $alteration->date,
            // 'date_logded' => $alteration,
            // 'is_quote_sent' => (is_null($alteration->is_quote_sent)) ? 'False' : 'True',
            // 'payment_date' => $alteration->client_paid_at,
            // 'invoice_number' => null,
            // 'payment_to_liquour_board' => $alteration->payment_to_liqour_board_at,
            // 'renewal_granted' => $alteration->renewal_issued_at,
            // 'delivery_date' => $alteration->renewal_delivery_at,
            // 'proof_of_delivery' => null,
            // 'notes' => $notesCollection
        ]);
    }
    
   
   
}

public function forceDownload(){
   return Excel::download(new AlterationExports, 'alteration.xlsx');
}
}
