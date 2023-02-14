<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Licence;
use App\Models\NewAppExport;
use App\Exports\NewAppsExport;
use App\Models\LicenceDocument;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        $licences = Licence::where(function($query) use($request){
            $query->when(request('activeStatus') === 'Active', function ($query) {
                        $query->where('is_licence_active',true);
                    })
                    ->when(request('activeStatus') === 'Inactive', function ($query) {
                        $query->where('is_licence_active',false);
                    })

             ->when(request('month_from') && request('month_to'), function($query){
                $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
             })

            ->when(request('month_from') && !request('month_to'), function ($query)  {
                $query->whereMonth('licence_date', request('month_from'));
            })
            ->when(!empty(request('province')), function ($query) {
                $query->whereIn('province',array_values(explode(",",request('province'))));
            })
            ->when(!empty(request('boardRegion')), function ($query) {
                $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
            })
            ->when(request('new_app_stages'), function ($query) {
                $query->whereIn('status',array_values(explode(",",request('new_app_stages'))));
            })
            ->when(!empty(request('applicant')), function ($query) use ($request) {
                $query->where('belongs_to',$request->applicant);
            })
            ->when(!empty(request('licence_types')), function ($query) use ($request) {
                $query->where('licence_type_id',$request->licence_types);
            })
            ->when(!empty(request('selectedDates')), function ($query) use ($request) {
               //$query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
            });
        })->where('is_new_app',true)->get(['id','trading_name','licence_number','licence_type_id','province','deposit_paid_at',
        'application_lodged_at','activation_fee_paid_at','client_paid_at','status','is_new_app']);

    $status = '';
    $notesCollection = '';

    $arr_of_licences = $licences->toArray(); 

for($i = 0; $i < count($arr_of_licences); $i++ ){
    //dd($arr_of_licences[$i]->licence_type->licence_type);
            switch ($arr_of_licences[$i]['status']) {
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
                     return back()->with('error','Could not process request.An unknown error occured');
                     break;
            }

            $notes = Task::where('model_id',$arr_of_licences[$i]['id'])->where('model_type','Licence')->get(['body']);
            //check if client has been logded
            $is_client_logded = LicenceDocument::where('licence_id',$arr_of_licences[$i]['id'])->where('document_type','Application Lodged')->first(['document_name']);


        if(!is_null($notes) || !empty($notes)){
            foreach ($notes as $note) {
              $notesCollection .=  $note->body. ' ';
            }
            }

            $data = [ 

            $arr_of_licences[$i]['trading_name'], 
            $arr_of_licences[$i]['licence_number'], 
            $arr_of_licences[$i]->licence_type->licence_type, 
            $arr_of_licences[$i]['province'],
            'NULL',
            is_null($arr_of_licences[$i]['deposit_paid_at']) ? 'FALSE': 'TRUE',
            optional($arr_of_licences[$i]['application_lodged_at'])->format('d M Y'),
            is_null($is_client_logded) ? 'FALSE': 'TRUE',
            $arr_of_licences[$i]['activation_fee_paid_at'],
            'NULL',
            optional($arr_of_licences[$i]['client_paid_at'])->format('d M Y'),
            'NULL',
            $status,
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
        header('Content-Disposition: attachment;filename="registrations_'.now()->format('d_m_y').'.xlsx"');
        header('Cache-Control: max-age=0');        
        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
}

}
