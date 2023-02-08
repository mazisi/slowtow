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
        $exists = AlterationExport::where('user_id',auth()->id())->get(['id']);                
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
                                    $query->when(request('month_from') && request('month_to'), function($query){
                                        $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                                     })
                        
                                    ->when(request('month_from') && !request('month_to'), function ($query)  {
                                        $query->whereMonth('licence_date', request('month_from'));
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
                            ->get('id','trading_name','licence_number','board_region','province','application_lodged_at','licence_issued_at');
      
    $notesCollection = '';
    $status = '';

    foreach ($alterations as $alteration) {
        $notes = Task::where('model_id',$alteration->id)->where('model_type','Alteration')->get(['body']);
    
        if(!is_null($notes) || !empty($notes)){
            foreach ($notes as $note) {
                $notesCollection .= $note->body. ' ';
            }
        }
        switch ($alteration->status) {
            case '1':
               $status = 'Client Invoiced';
                break;
            case '2':
                $status = 'Client Paid';
                break;
            case '3':
                $status = 'Alteration Details Captured';
                break;
            case '4':
                $status = 'Alteration Complete';
                break;
            
            default:
                return back()->with('error','Could not process request.An unknown error occured');
                break;
            }
        AlterationExport::create([
            'user_id' => auth()->id(),
            'trading_name' => $alteration->trading_name,
            'licence_number' => $alteration->licence_number,
            'province' => $alteration->province.'/'.$alteration->board_region,
            'date_logded' => $alteration->application_lodged_at,
            'status' => $status,
            'proof_lodgiment' => NULL,
            'date_granted' => $alteration->licence_issued_at,
            'notes' => $notesCollection
        ]);
    }
    
   
   
}

public function forceDownload(){
   return Excel::download(new AlterationExports, 'alterations.xlsx');
}
}
