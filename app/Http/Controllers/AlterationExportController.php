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



        $alterations = DB::table('alterations')
                            ->selectRaw("alterations.id, licences.trading_name, licences.licence_number, licences.province, 
                            licences.licence_issued_at, licences.application_lodged_at,alterations.status ")
                            ->join('licences', 'licences.id' , '=', 'alterations.licence_id' )
                                ->when(function($query){
                                    $query->when(request('month'), function($query){
                                        $query->whereMonth('licence_date', request('month'));
                                    })
                                    ->when(request('activeStatus') == 'Active', function ($query) {
                                        $query->where('is_licence_active',true);
                                    })
                                    ->when(request('activeStatus') == 'Inactive', function ($query) {
                                        $query->where('is_licence_active',false);
                                    })
                                    ->when(request('province'), function ($query) {
                                        $query->whereIn('province',request('province'));
                                    })
                                    ->when(request('boardRegion'), function ($query) {
                                        $query->whereIn('board_region',request('boardRegion'));
                                    })
                                    ->when(request('applicant'), function ($query) {
                                        $query->where('belongs_to',request('applicant'));
                                    });

                                })
                                ->when(request('alteration_stages'), function ($query) {
                                    $query->whereIn('alterations.status',request('alteration_stages'));
                              })
                            ->get();
      
    $notesCollection = '';

    foreach ($alterations as $alteration) {
        $notes = Task::where('model_id',$alteration->id)->where('model_type','Alteration')->get(['body']);
    
        if(!is_null($notes) || !empty($notes)){
            foreach ($notes as $note) {
                $notesCollection .= ' '. $note->body;
            }
        }
        AlterationExport::create([
            'user_id' => auth()->id(),
            'trading_name' => $alteration->trading_name,
            'licence_number' => $alteration->licence_number,
            'province' => $alteration->province,
            'date_logded' => $alteration->application_lodged_at,
            'date_granted' => $alteration->licence_issued_at,
            'notes' => $notesCollection
        ]);
    }
    
   
   
}

public function forceDownload(){
   return Excel::download(new AlterationExports, 'alterations.xlsx');
}
}
