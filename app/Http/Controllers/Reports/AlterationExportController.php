<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\AlterationDocument;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AlterationExportController extends Controller
{
    public static function export($request){
        $arrayData = array(
            array(                
                'TRADING NAME',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DATE LODGED',
                'PROOF OF LODGEMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'COMMENT IF APPLICABLE'
            )
        );
        $arr_of_alterations = [];


        $alterations = DB::table('alterations')
                            ->selectRaw("alterations.id, alterations.certification_issued_at, licences.trading_name, licences.licence_number, licences.province, 
                            licences.licence_issued_at, alterations.logded_at,licences.board_region,licence_type_id,alterations.date, 
                            alterations.status, licence_date")
                            ->join('licences', 'licences.id' , '=', 'alterations.licence_id' )

                                ->when($request,function($query){
                                    $query->when(request('month_from') && request('month_to'), function($query){
                                        $query->whereBetween(DB::raw('MONTH(alterations.logded_at)'),[request('month_from'), request('month_to')]);
                                     })
                        
                                    ->when(request('month_from') && !request('month_to'), function ($query)  {
                                        $query->whereMonth('alterations.logded_at', request('month_from'));
                                    })
                                    ->when(request('activeStatus') == 'Active', function ($query) {
                                        $query->where('is_licence_active',1);
                                    })
                                    ->when(request('activeStatus') == 'Inactive', function ($query) {
                                        $query->where('is_licence_active',false);
                                    })
                                    ->when(request('province'), function ($query) {
                                        $query->whereIn('province',array_values(explode(",",request('province'))));
                                    })
                                    
                                    ->when(request('boardRegion'), function ($query) {
                                        $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
                                    })
                                    
                                     ->when(request('alteration_stages'), function ($query) {
                                            $query->whereIn('alterations.status',array_values(explode(",",request('alteration_stages'))));
                                        })
                                    ->when(request('applicant'), function ($query) {
                                        $query->where('belongs_to',request('applicant'));
                                    })
                                    
                                    ->when(request('year') && request('year') !== 'null', function ($query) {
                                         $query->whereYear('logded_at', request('year'));
                                     })
                                    ->when(request('licence_types'), function ($query) {
                                        $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
                                     })

                                    
                                    ->when(request('is_licence_complete') === 'Pending' , function ($query){
                                            $query->where(function ($query) {
                                                $query->where('alterations.status','<', intval(8));
                                            });
                                        
                                        })
                
                                    ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                                        $query->where('alterations.status',8);
                                    });

                                })
                                ->whereNull('alterations.deleted_at')
                                ->orderBy('trading_name')
                                 ->get([
                                    'certification_issued_at',
                                    'id','trading_name',
                                    'licence_number',
                                    'board_region',
                                    'province',
                                    'status',
                                    'board_region',
                                    'doc_type',
                                    'date',
                                    'licence_issued_at',
                                    'licence_type_id',
                                    'belongs_to',
                                    'logded_at'
                            ]);

                    $status = '';
                    

                    $arr_of_alterations = $alterations->toArray(); 

                for($i = 0; $i < count($arr_of_alterations); $i++ ){
                        switch ($arr_of_alterations[$i]->status) {
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
                            $status = 'Prepare Alterations Application';
                            break;
                        case '5':
                            $status = 'Payment to the Liquor Board';
                            break;
                        case '6':
                            $status = 'Alterations Lodged';
                            break;
                        case '7':
                            $status = 'Alterations Certificate Issued';
                            break;
                        case '8':
                            $status = 'Alterations Delivered';
                            break;
                        default:
                            $status = '';
                            break;
                        }

                        $notes = Task::where('model_id',$arr_of_alterations[$i]->id)->where('model_type','Alteration')->get(['body','created_at']);
                        $notesCollection = '';
                        $proof_of_logdiment = AlterationDocument::where('alteration_id',$arr_of_alterations[$i]->id)->where('doc_type','Alterations Lodged')->first(['id']);


                        if($notes){
                            foreach ($notes as $note) {
                            $notesCollection .=  $note->created_at.' '.$note->body. ' ';
                            }
                        }
                        
                        
                        
                        $data = [
                        $arr_of_alterations[$i]->trading_name, 
                        $arr_of_alterations[$i]->licence_number, 
                        request('boardRegion') ? $arr_of_alterations[$i]->province.'-'.$arr_of_alterations[$i]->board_region : $arr_of_alterations[$i]->province,
                        $arr_of_alterations[$i]->logded_at,
                        $proof_of_logdiment ? 'TRUE' : 'FALSE',
                        $arr_of_alterations[$i]->certification_issued_at,
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

                $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setWrapText(true);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="alterations_'.now()->format('d_m_y').'.xlsx"');
                header('Cache-Control: max-age=0');        
                $writer = new Xlsx($spreadsheet);
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                die;
    
   
   
}


}