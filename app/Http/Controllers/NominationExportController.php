<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\NominationExport;
use App\Exports\NominationExports;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

ini_set('memory_limit', '1024M');

class NominationExportController extends Controller
{
    public static function export($request){

          try {
            $exists = NominationExport::where('user_id',auth()->id())->get();
                      
            if(!is_null($exists)){
                foreach ($exists as $exist) {
                    $exist->delete();
                }                  
            }

       
                $nominations = DB::table('nominations')
                            ->selectRaw("nominations.id, licences.trading_name, people.full_name, licences.licence_number, licences.province, 
                                         nominations.payment_to_liquor_board_at, nominations.nomination_lodged_at, 
                                nomination_lodged_at,nomination_lodged_at, '' as date_granted , 
                                nominations.status ")
                            ->join('nomination_people', 'nomination_people.nomination_id' , '=', 'nominations.id' )
                            ->join('people', 'people.id' , '=', 'nomination_people.people_id' )
                            ->join('licences', 'licences.id' , '=', 'nominations.licence_id' )
                                ->when(function($query){
                                    $query->when(request('month'), function($query){
                                        $query->whereMonth('licence_date', request('month'));
                                    })
                                    ->when(request('activeStatus') == 'Active', function ($query) {
                                        $query->whereNotNull('is_licence_active');
                                    })
                                    ->when(request('activeStatus') == 'Inactive', function ($query) {
                                        $query->whereNull('is_licence_active');
                                    })
                                    ->when(request('province'), function ($query) {
                                        $query->whereIn('province',request('province'));
                                    })
                                    ->when(request('licence_types'), function ($query)  {
                                        $query->whereIn('licence_type_id',request('licence_types'));
                                    })

                                    ->when(request('boardRegion'), function ($query) {
                                        $query->whereIn('board_region',request('boardRegion'));
                                    })
                                    ->when(request('applicant'), function ($query) {
                                        $query->where('belongs_to',request('applicant'));
                                    });

                                })->when(request('selectedDates'), function ($query) {
                                      $query->whereIn('year',request('selectedDates'));
                                })
                                ->when(request('nomination_stages'), function ($query) {
                                    $query->whereIn('nominations.status',request('nomination_stages'));
                              })
                            ->get();
        
        $status = '';
        $notesCollection = '';
        
        foreach ($nominations as $nom) {
            
            $notes = Task::where('model_id',$nom->id)->where('model_type','Nomination')->get(['body']);

       // $is_client_paid = NominationDocument::where('nomination_id',$nom->id)->where('doc_type','Payment To The Liquor Board')->first();
            if(!is_null($notes) || !empty($notes)){
                foreach ($notes as $note) {
                    $notesCollection .=  $note->body .' ->';
                }
            }
            switch ($nom->status) {
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
                    $status = 'Payment To The Liquor Board';
                    break;
                case '5':
                    $status = 'Select Nominees';
                    break;
                case '6':
                    $status = 'Prepare Nomination Application';
                    break;
                case '7':
                    $status = 'Scanned Application';
                    break;
                case '8':
                    $status = 'Nomination Lodged';
                    break;
                case '9':
                    $status = 'Nomination Issued';
                    break;
                case '10':
                    $status = 'Nomination Delivered';
                    break;
                default:
                    //
                    break;
            }

            NominationExport::create([
                'user_id' => auth()->id(),
                'trading_name' => $nom->trading_name,
                'client_name' =>  $nom->full_name,
                'licence_number' => $nom->licence_number,
                'province' => $nom->province,
                'invoice_number' => null,
                'payment_date' => $nom->payment_to_liquor_board_at,
                'date_logded' => $nom->nomination_lodged_at,
                'proof_of_lodgement' => (is_null($nom->nomination_lodged_at)) ? 'FALSE' : 'TRUE',
                'date_granted' => '',
                'current_status' => $status,                
                'notes' => $notesCollection
            ]);
        }
          } catch (\Throwable $th) {
            //throw $th;
           return back()->with('error','An unknown error occured.');
         }
    
        
}


public function forceDownload() {
    return Excel::download(new NominationExports(), 'nominations.xlsx');
}
}
