<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransferExportController extends Controller
{
public static function export($request){
    $arrayData = array(
        array(
            'CURRENT TRADING NAME',
            'GAU/GLB No',
            'PROVINCE/REGION',
            'DEPOSIT INVOICE',
            'DEPOSIT PAID',
            'DATE LODGED',
            'PROOF OF LODGEMENT',
            'FINALISATION INVOICE',
            'FINALISATION PAYMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'COMMENTS'
        )
    );
    $arr_of_transfers = [];
    
                $transfers = DB::table('licence_transfers')
                    ->selectRaw("licence_transfers.id, is_licence_active, trading_name, licence_transfers.date, 
                                 licence_transfers.lodged_at, licence_transfers.status, payment_to_liquor_board_at, 
                                 board_region,issued_at, delivered_at,province, licence_number")

                    ->join('licences', 'licences.id' , '=', 'licence_transfers.licence_id')

                    ->when(function($query){
                        $query->when(request('month_from') && request('month_to'), function($query){
                            $query->whereBetween(DB::raw('MONTH(date)'),[request('month_from'), request('month_to')]);
                        })
              
                        ->when(request('month_from') && !request('month_to'), function ($query)  {
                            $query->whereMonth('date', request('month_from'));
                        })
                        ->when(request('province'), function ($query)  {
                            $query->whereIn('licences.province',array_values(explode(",",request('province'))));
                        })
                        ->when(request('boardRegion'), function ($query)  {
                            $query->whereIn('licences.board_region',array_values(explode(",",request('boardRegion'))));
                        })
                        
                        ->when(request('applicant'), function ($query)  {
                            $query->where('belongs_to',request('applicant'));
                        })

                        ->when(request('activeStatus') === 'Inactive', function ($query) {
                            $query->where('is_licence_active',false);
                        })

                        ->when(request('activeStatus') == 'Active', function ($query) {
                            $query->where('is_licence_active', 1);
                        })

                        ->when(request('licence_types'), function ($query)  {
                            $query->where('licence_type_id',request('licence_types'));
                        })

                        ->when(request('is_licence_complete') === 'Pending', function ($query)  {
                            $query->where('licence_transfers.status','<', 10)
                                    ->orWhereNull('licence_transfers.status');
                        })
    
                        ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                            $query->where('licence_transfers.status','=',10);
                        });

                    })->when(request('year'), function ($query) {
                          $query->whereYear('date',request('year'));
                    })
                    ->when(request('transfer_stages'), function ($query) {
                        $query->whereIn('licence_transfers.status', array_values(explode(",",request('transfer_stages'))));
                    })
                    ->whereNull('licences.deleted_at')->whereNull('licence_transfers.deleted_at')
                    ->orderBy('trading_name')->get([
                        'trading_name',
                        'licence_number',
                        'province',
                        'board_region',
                        'lodged_at',
                        'payment_to_liquor_board_at',
                        'issued_at',
                        'status'
                    ]);

            $status = '';
            

            $arr_of_transfers = $transfers->toArray(); 

            for($i = 0; $i < count($arr_of_transfers); $i++ ){
                switch ($arr_of_transfers[$i]->status) {
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
                        $status = 'Prepare Transfer Application';
                        break;
                    case '5':
                        $status = 'Payment To The Liquor Board';
                        break;
                    case '6':
                        $status = 'Scanned Application';
                        break;
                    case '7':
                        $status = 'Application Logded';
                        break;
                    case '8':
                        $status = 'Activation Fee Paid';
                        break;
                    case '9':
                        $status = 'Transfer Issued';
                        break;
                    case '10':
                        $status = 'Transfer Delivered';
                        break;
                    default:
                        $status = '';
                        break;
                }

                $notes = Task::where('model_id',$arr_of_transfers[$i]->id)->where('model_type','Transfer')->get(['body','created_at']);
                $notesCollection = '';
            
                    if($notes){
                        foreach ($notes as $note) {
                            $notesCollection .=  $note->created_at.'   '.$note->body. '   ';
                        }
                    }
        
  
            $data = [         
                       $arr_of_transfers[$i]->trading_name, 
                       $arr_of_transfers[$i]->province == 'Gauteng' ? $arr_of_transfers[$i]->licence_number : '',
                       request('boardRegion') ? $arr_of_transfers[$i]->province.' - '.$arr_of_transfers[$i]->board_region : $arr_of_transfers[$i]->province,
                       '',
                       '',
                       $arr_of_transfers[$i]->lodged_at,
                       $arr_of_transfers[$i]->lodged_at ? 'FALSE' : 'TRUE',
                       '',
                       $arr_of_transfers[$i]->payment_to_liquor_board_at,
                       $arr_of_transfers[$i]->issued_at,
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
     
     $spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
     $spreadsheet->getActiveSheet()->getStyle('A1:L1')
     ->getAlignment()->setWrapText(true);

     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     header('Content-Disposition: attachment;filename="Transfers_'.now()->format('d_m_y').'.xlsx"');
     header('Cache-Control: max-age=0');        
    $writer = new Xlsx($spreadsheet);
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    die;

                }



                


}