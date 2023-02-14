<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RenewalExportController extends Controller
{

    
    public static function export($request){
        $arrayData = array(
            array(
                'ACTIVE/DEACTIVE',
                'TRADING NAME',
                'LICENCE NUMBER',
                'RENEWAL DATE',
                'RENEWAL AMOUNT',
                'QUOTED',
                'QUOTE SENT',
                'PAYMENT DATE',
                'INVOICE NUMBER',
                'PAYMENT TO LIQUOR BOARD',
                'RENEWAL GRANTED',
                'DELIVERY DATE ',
                'PROOF OF DELIVERY',
                'REASON / NOTES'
            )
        );
        $arr_of_renewals = [];
                    $renewals = DB::table('licence_renewals')
                    ->selectRaw("licence_renewals.id, is_licence_active, trading_name, licence_number, licence_renewals.date, 
                                 licence_renewals.client_paid_at,licence_renewals.status, payment_to_liquor_board_at, renewal_issued_at, renewal_delivered_at,
                                 is_quote_sent")

                    ->join('licences', 'licences.id' , '=', 'licence_renewals.licence_id')

                        ->when(function($query){
                            $query->when(request('month_from') && request('month_to'), function($query){
                                $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                            })
                  
                            ->when(request('month_from') && !request('month_to'), function ($query)  {
                                $query->whereMonth('licence_date', request('month_from'));
                            })

                            ->when(request('province'), function ($query)  {
                                $query->whereIn('province',array_values(explode(",",request('province'))));
                            })

                            ->when(request('boardRegion'), function ($query)  {
                                $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
                            })
                            ->when(request('applicant'), function ($query)  {
                                $query->where('belongs_to',request('applicant'));
                            })
                            ->when(request('licence_types'), function ($query)  {
                                $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
                            });

                            })->when(request('selectedDates'), function ($query) {
                                $query->whereIn('year',array_values(explode(",",request('selectedDates'))));
                            })

                            ->when(request('renewal_stages'), function ($query) {
                                $query->whereIn('licence_renewals.status',array_values(explode(",",request('renewal_stages'))));
                            })
                           ->get([
                                'id',
                                'is_licence_active',
                                'trading_name',
                                'licence_number',
                                'date',
                                'is_quote_sent',
                                'client_paid_at',
                                'payment_to_liquor_board_at',
                                'renewal_issued_at',
                                'renewal_delivered_at',
                            ]
                           );

        
            $notesCollection = ' ';
            
            $arr_of_renewals = $renewals->toArray(); 

            for($i = 0; $i < count($arr_of_renewals); $i++ ){

                $notes = Task::where('model_id',$arr_of_renewals[$i]->id)->where('model_type','Licence Renewal')->get(['body']);

                //check if client has been quoted
                       $is_quoted = RenewalDocument::where('licence_renewal_id',$arr_of_renewals[$i]->id)->where('doc_type','Client Quoted')->first(['id']);
    
                    if(!is_null($notes) || !empty($notes)){
                        foreach ($notes as $note) {
                            $notesCollection .=  $note->body. ' ';
                        }
                    }

            $data = [ 

                       $arr_of_renewals[$i]->is_licence_active ? 'A' : 'D',
                       $arr_of_renewals[$i]->trading_name, 
                       $arr_of_renewals[$i]->licence_number,
                       $arr_of_renewals[$i]->date,
                       'NULL',
                       is_null($is_quoted) ? 'FALSE' : 'TRUE',
                       is_null($arr_of_renewals[$i]->is_quote_sent) ? 'FALSE' : 'TRUE',
                       $arr_of_renewals[$i]->client_paid_at,
                       'NULL',
                       $arr_of_renewals[$i]->payment_to_liquor_board_at,
                       $arr_of_renewals[$i]->renewal_issued_at,
                       $arr_of_renewals[$i]->renewal_delivered_at,
                       'NULL',
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

                    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
                    
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="renewals_'.now()->format('d_m_y').'.xlsx"');
                    header('Cache-Control: max-age=0');        
                    $writer = new Xlsx($spreadsheet);
                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
    
           
    }

   

}
