<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class NominationExportController extends Controller
{
    public static function export($request){

          try {
            $arrayData = array(
                array(
                    'TRADING NAME',
                    'Client Name',
                    'LICENCE NUMBER',
                    'PROVINCE/REGION',
                    'INVOICE NUMBER',
                    'PAYMENT DATE',
                    'DATE LODGED',
                    'PROOF OF LODGEMENT',
                    'DATE GRANTED',
                    'CURRENT STATUS',
                    'COMMENTS'
                )
            );
            $arr_of_nominations = [];
            
                  
                $nominations = DB::table('nominations')
                            ->selectRaw("nominations.id, licences.trading_name, people.full_name, licences.licence_number, licences.province, 
                                         nominations.payment_to_liquor_board_at, nominations.nomination_lodged_at, 
                                nomination_lodged_at,nomination_lodged_at, '' as date_granted , 
                                nominations.status, nominations.nomination_issued_at,board_region, nominations.year")
                            ->join('nomination_people', 'nomination_people.nomination_id' , '=', 'nominations.id' )
                            ->join('people', 'people.id' , '=', 'nomination_people.people_id' )
                            ->join('licences', 'licences.id' , '=', 'nominations.licence_id' )
                                ->when($request,function($query){
                                    $query->when(request('month_from') && request('month_to'), function($query){
                                        $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                                    })
                        
                                    ->when(request('month_from') && !request('month_to'), function ($query)  {
                                        $query->whereMonth('licence_date', request('month_from'));
                                    })
                                    ->when(request('activeStatus') == 'Active', function ($query) {
                                        $query->whereNotNull('is_licence_active');
                                    })
                                    ->when(request('activeStatus') == 'Inactive', function ($query) {
                                        //$query->whereNull('is_licence_active');
                                    })
                                    ->when(request('province'), function ($query) {
                                        $query->whereIn('province',array_values(explode(",",request('province'))));
                                    })
                                    ->when(request('licence_types'), function ($query)  {
                                        $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
                                    })

                                    ->when(request('boardRegion'), function ($query) {
                                        $query->whereIn('board_region', array_values(explode(",",request('boardRegion'))));
                                    })
                                    ->when(request('applicant'), function ($query) {
                                        $query->where('belongs_to',request('applicant'));
                                    });

                                })
                                ->when(request('nomination_stages'), function ($query) {
                                    $query->whereIn('nominations.status',array_values(explode(",",request('nomination_stages'))));
                              })

                              ->when(request('is_licence_complete') === 'Pending', function ($query)  {
                                $query->where('nominations.status','<', 10)
                                ->orWhereNull('nominations.status');
                            })
        
                            ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                                $query->where('nominations.status','=', 10);
                            })
                            
                            ->when(request('year'), function ($query) {
                                       $query->where('nominations.year', request('year'));
                                    })
                              ->whereNull('licences.deleted_at')->whereNull('nominations.deleted_at')
                              
                              ->orderBy('trading_name')
                                ->get([
                                    'id',
                                    'trading_name',
                                    'full_name',
                                    'licence_number',
                                    'board_region',
                                    'province',
                                    'payment_to_liquor_board_at',
                                    'nomination_lodged_at',
                                    'year',
                                    'nominations.status'
                                ]);
       
                            $status = '';
                            $notesCollection = '';
                
                            $arr_of_nominations = $nominations->toArray(); 
        
        for($i = 0; $i < count($arr_of_nominations); $i++ ){
            switch ($arr_of_nominations[$i]->status) {
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
                     $status = 'Null';
                     break;
            }

            $notes = Task::where('model_id',$arr_of_nominations[$i]->id)->where('model_type','Nomination')->get(['body','created_at']);

            // $is_client_paid = NominationDocument::where('nomination_id',$arr_of_nominations[$i]->id)->where('doc_type','Payment To The Liquor Board')->first();

        
                if(!is_null($notes) || !empty($notes)){
                    foreach ($notes as $note) {
                        $notesCollection .=  $note->created_at.' '.$note->body. ' ';
                    }
                }

       $data = [ 
                   $arr_of_nominations[$i]->trading_name, 
                   $arr_of_nominations[$i]->full_name, 
                   $arr_of_nominations[$i]->licence_number, 
                   $arr_of_nominations[$i]->board_region ? $arr_of_nominations[$i]->province.' - '.$arr_of_nominations[$i]->board_region : $arr_of_nominations[$i]->province,
                   '',
                   $arr_of_nominations[$i]->payment_to_liquor_board_at,
                   $arr_of_nominations[$i]->nomination_lodged_at,
                   (is_null($arr_of_nominations[$i]->nomination_lodged_at)) ? 'FALSE' : 'TRUE',
                   $arr_of_nominations[$i]->nomination_issued_at,
                   $status,
                   $notesCollection
                ];

        $arrayData[] = $data;

            }

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()
             ->fromArray(
             $arrayData,   // The data to set
             NULL,        // Array values with this value will not be set
             'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
             );
 
    foreach ($spreadsheet->getActiveSheet()->getColumnIterator() as $column) {
                 $spreadsheet->getActiveSheet()->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
              }
 
     $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
     $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setWrapText(true);
     
     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     header('Content-Disposition: attachment;filename="Nominations_'.now()->format('d_m_y').'.xlsx"');
     header('Cache-Control: max-age=0');   
          
    $writer = new Xlsx($spreadsheet);
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    die;

          } catch (\Throwable $th) {
            //throw $th;
          return back()->with('error','An unknown error occured.');
         }
    
        
}

}
