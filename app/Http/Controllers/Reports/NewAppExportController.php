<?php

namespace App\Http\Controllers;

use App\Models\Task;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class NewAppExportController extends Controller
{
    public static function export($request){
        $arrayData = array(
            array(
            'TRADING NAME',
            'LICENCE TYPE',
            'LICENCE NUMBER',
            'PROVINCE/REGION',
            'DEPOSIT INVOICE',
            'DEPOSIT PAID',
            'DATE LODGED',
            'PROOF OF LODGEMENT',
            'ACTIVATION FEE PAID',
            'FINAL INVOICE',
            'FULL PAYMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'FOCUS FOR THE WEEK',
            'COMMENTS'
            )
        );
        $arr_of_licences = [];

        $licences = DB::table('licences')
                 ->selectRaw("licences.id, is_licence_active, trading_name,licence_type_id, licence_types.licence_type, province, licence_number,
                              deposit_paid_at, application_lodged_at, activation_fee_paid_at, licence_issued_at,client_paid_at,
                              client_paid_at,status, board_region,licence_date, is_new_app")

                 ->join('licence_types', 'licences.licence_type_id' , '=', 'licence_types.id')

                     ->when($request,function($query){
                        $query->when(request('month_from') && request('month_to'), function($query){
                            $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                         })
               
                         ->when(request('month_from') && !request('month_to'), function ($query){
                            $query->whereMonth('licence_date', request('month_from'));
                        })

                        ->when(request('activeStatus') === 'Active', function ($query) {
                            $query->where('is_licence_active',1);
                        })
                        ->when(request('activeStatus') === 'Inactive', function ($query) {
                            $query->where('is_licence_active',0);
                        })

                        ->when(request('province'), function ($query) {
                            $query->whereIn('province',array_values(explode(",",request('province'))));
                        })
                        ->when(request('boardRegion'), function ($query) {
                            $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
                        })
                        ->when(request('new_app_stages'), function ($query) {
                            $query->whereIn('status', array_values(explode(",",request('new_app_stages'))));
                        })
                        ->when(request('applicant'), function ($query) {
                            $query->where('belongs_to',request('applicant'));
                        })
                        ->when(request('licence_types'), function ($query) {
                            $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
                        })
                        
                        ->when(request('year') && request('year') !== 'null', function ($query) {
                               $query->whereYear('licence_date', request('year'));
                         })

                       // ->when(request('selectedDates'), function ($query) {
                            //$query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
                         //})

                         ->when(request('is_licence_complete') === 'Pending', function ($query)  {
                            $query->where('status','<', intval(16))
                            ->orWhereNull('status');
                        })
    
                        ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                            $query->where('status','=', 16);
                        });
                        
                        })
                        
                        ->whereNull('deleted_at')
                        ->where('is_new_app',1)
                        ->orderBy('trading_name')
                        ->get([
                            'id',
                            'trading_name',
                            'licence_number',
                            'licence_type_id',
                            'licence_type',
                            'province',
                            'board_region',
                            'licence_date',
                            'deposit_paid_at',
                            'licence_issued_at',
                            'application_lodged_at',
                            'activation_fee_paid_at',
                            'client_paid_at','status',
                            'is_new_app'
                            ]);

            $status = '';
           
            
            $arr_of_licences = $licences->toArray(); 

            for($i = 0; $i < count($arr_of_licences); $i++ ){

                switch ($arr_of_licences[$i]->status) {
                    case '1':
                       $status = 'Client Quoted';
                        break;
                    case '2':
                        $status = 'Deposit Paid';
                        break;
                    case '3':
                        $status = 'Client Invoiced';
                        break;
                    case '4':
                        $status = 'Prepare New Application';
                        break;
                    case '5':
                        $status = 'Payment To The Liquor Board';
                        break;
                    case '6':
                        $status = 'Scanned Application';
                        break;
                    case '7':
                        $status = 'Application Lodged';
                        break;
                    case '8':
                        $status = 'Initial Inspection';
                        break;
                    case '9':
                        $status = 'Liquor Board Requests';
                        break;
                    case '10':
                        $status = 'Final Inspection';
                        break;
                    case '11':
                        $status = 'Activation Fee Requested';
                        break;
                    case '12':
                        $status = 'Client Finalisation Invoice';
                        break;
                    case '13':
                        $status = 'Client Paid';
                        break;
                    case '14':
                        $status = 'Activation Fee Paid';
                        break;
                    case '15':
                        $status = 'Licence Issued';
                        break;
                    case '16':
                        $status = 'Licence Delivered';
                        break;
                    default:
                        $status='';
                        break;
                    }

                    $notes = Task::where('model_id',$arr_of_licences[$i]->id)->where('model_type','Licence')->get(['body','created_at']);
                    $notesCollection = '';
                    if($notes){
                        foreach ($notes as $note) {
                            $notesCollection .=  $note->created_at.'     '.$note->body. ' ';
                        }
                    }

               $data = [ 
                       $arr_of_licences[$i]->trading_name, 
                       $arr_of_licences[$i]->licence_type,
                       $arr_of_licences[$i]->licence_number,
                       request('boardRegion') ? $arr_of_licences[$i]->province.' - '.$arr_of_licences[$i]->board_region : $arr_of_licences[$i]->province,
                       '',
                       $arr_of_licences[$i]->deposit_paid_at ? 'FALSE': 'TRUE',
                       $arr_of_licences[$i]->application_lodged_at ? date('d M Y', strtotime($arr_of_licences[$i]->application_lodged_at)) : '',
                       $arr_of_licences[$i]->application_lodged_at ? 'FALSE': 'TRUE',
                       $arr_of_licences[$i]->activation_fee_paid_at,
                       '',
                       $arr_of_licences[$i]->client_paid_at ? date('d M Y', strtotime($arr_of_licences[$i]->client_paid_at)) : '',
                       $arr_of_licences[$i]->licence_issued_at ? date('d M Y', strtotime($arr_of_licences[$i]->licence_issued_at)) : '',
                       $status,
                       '',
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

                $spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setWrapText(true);
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="New_Apps'.now()->format('d_m_y').'.xlsx"');
                header('Cache-Control: max-age=0');        
                $writer = new Xlsx($spreadsheet);
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                die;
   
}


}