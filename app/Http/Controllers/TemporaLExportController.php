<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\Models\TemporalLicenceDocument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TemporaLExportController extends Controller
{
    public static function export($request){
        $arr_of_licences = [];
        $arrayData = array(
            array(
            'EVENT NAME',
            'APPLICANT',
            'EVENT DATES',
            'INVOICE NUMBER',
            'PROVINCE/REGION',
            'PAYMENT DATE',
            'LICENCE NUMBER',
            'DATE LODGED',
            'PROOF OF LODGEMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'COMMENTS'
            )
        );

       $people_temp_licences = DB::table('temporal_licences')
       ->join('people', 'temporal_licences.people_id', '=', 'people.id')       
                    
            ->when($request,function($query){
                $query->when(request('month_from') && request('month_to'), function($query){
                    $query->whereBetween(DB::raw('MONTH(start_date)'),[request('month_from'), request('month_to')]);
                })
                
                ->when(request('month_from') && !request('month_to'), function ($query)  {
                    $query->whereMonth('start_date', request('month_from'));
                })
                    ->when(request('temp_licence_stages'), function ($query) {
                        $query->whereIn('temporal_licences.status',array_values(explode(",",request('temp_licence_stages'))));
                    })
                    ->when(request('activeStatus') === 'Active', function ($query) {
                        $query->where('active',true);
                    })
                    ->when(request('activeStatus') === 'Inactive', function ($query) {
                        $query->where('active',false);
                     })

                     ->when(!empty(request('temp_licence_region')), function ($query) {
                        $query->whereIn('address',array_values(explode(",",request('temp_licence_region'))));
                    })

                    ->when(!empty(request('selectedDates')), function ($query) {
                        $query->whereIn(DB::raw('MONTH(start_date)'),array_values(explode(",",request('selectedDates'))));
                    })
                    ->when(!empty(request('applicant')), function ($query) {
                        $query->where('belongs_to',request('applicant'));
                    })

                    ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                        $query->where('temporal_licences.status','<', 9)
                        ->orWhere('temporal_licences.status', 0)
                        ->orWhereNull('temporal_licences.status');
                    })

                    ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                        $query->where('temporal_licences.status',9);
                    });

                 })->whereNull('temporal_licences.deleted_at')
                 ->get(
                    [
                    'temporal_licences.id',
                    'event_name',
                    'belongs_to',
                    'address',
                    'client_paid_at',
                    'liquor_licence_number',
                    'latest_lodgment_date',
                    'delivered_at',
                    'status',
                    'full_name',
                    'start_date',
                    'end_date',
                ]);

                $company_temp_licences = DB::table('temporal_licences')
                ->join('companies', 'temporal_licences.company_id', '=', 'companies.id')   
                             
                     ->when($request,function($query){
                         $query->when(request('month_from') && request('month_to'), function($query){
                             $query->whereBetween(DB::raw('MONTH(start_date)'),[request('month_from'), request('month_to')]);
                         })
                         
                         ->when(request('month_from') && !request('month_to'), function ($query)  {
                             $query->whereMonth('start_date', request('month_from'));
                         })
                             ->when(request('temp_licence_stages'), function ($query) {
                                 $query->whereIn('temporal_licences.status',array_values(explode(",",request('temp_licence_stages'))));
                             })
                             ->when(request('activeStatus') === 'Active', function ($query) {
                                 $query->where('active',true);
                             })
                             ->when(request('activeStatus') === 'Inactive', function ($query) {
                                 $query->where('active',false);
                              })

                              ->when(!empty(request('temp_licence_region')), function ($query) {
                                $query->whereIn('address',array_values(explode(",",request('temp_licence_region'))));
                            })
                             ->when(!empty(request('selectedDates')), function ($query) {
                                 $query->whereIn(DB::raw('MONTH(start_date)'),array_values(explode(",",request('selectedDates'))));
                             })
                             ->when(!empty(request('applicant')), function ($query) {
                                 $query->where('belongs_to',request('applicant'));
                             })

                             ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                                $query->where('temporal_licences.status','<', 9)
                                ->orWhere('temporal_licences.status', 0)
                                ->orWhereNull('temporal_licences.status');
                            })
        
                            ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                                $query->where('temporal_licences.status',9);
                            });
                          })
                          
                          ->orderBy('event_name')
                          ->get(
                             [
                             'temporal_licences.id',
                             'event_name',
                             'belongs_to',
                             'address',
                             'client_paid_at',
                             'liquor_licence_number',
                             'latest_lodgment_date',
                             'delivered_at',
                             'start_date',
                             'end_date',
                             'status',
                             'name',
                         ]);
    $merged_data = $people_temp_licences->merge($company_temp_licences);

    $status = '';
    $applicant = '';
    $notesCollection = '';

    $arr_of_licences = $merged_data->toArray(); 

    for($i = 0; $i < count($arr_of_licences); $i++ ){

        switch ($arr_of_licences[$i]->status) {
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
                $status = 'Prepare Temporary Application';
                break;
            case '5':
                $status = 'Payment To The Liquor Board';
                break;
            case '6':
                $status = 'Scanned Application';
                break;
            case '7':
                $status = 'Temporary Licence Lodged ';
                break;
            case '8':
                $status = 'Temporary Licence Issued ';
                break;
            case '9':
                $status = 'Temporary Licence Delivered';
                break;
           
            default:
                $status = 'NULL';
                break;
            }

            
            switch ($arr_of_licences[$i]->belongs_to) {
                case 'Company':
                    $applicant = $arr_of_licences[$i]->name;
                    break;
                case 'Person':
                    $applicant = $arr_of_licences[$i]->full_name;
                    break;                
                default:
                    $applicant = "Null";
                    break;
            }

            $notes = Task::where('model_id',$arr_of_licences[$i]->id)->where('model_type','Temporal Licence')->get(['body','created_at']);
   
    $get_invoice_number = TemporalLicenceDocument::where('temporal_licence_id',$arr_of_licences[$i]->id)->where('doc_type','Client Invoiced')->first(['document_name']);
    $licence_logded = TemporalLicenceDocument::where('temporal_licence_id',$arr_of_licences[$i]->id)->where('doc_type','Licence Lodged')->first(['id']);

            if(!is_null($notes) || !empty($notes)){
                foreach ($notes as $note) {
                    $notesCollection .=  $note->created_at.' '.$note->body. ' ';
                }
            }

       $data = [ 

               $arr_of_licences[$i]->event_name, 
               $applicant,
               date('d-m-Y', strtotime($arr_of_licences[$i]->start_date)). ' - '.date('d-m-Y', strtotime($arr_of_licences[$i]->end_date)),
               $arr_of_licences[$i]->address,
               is_null($get_invoice_number) ? '' : $get_invoice_number->document_name,
               $arr_of_licences[$i]->client_paid_at,
               $arr_of_licences[$i]->liquor_licence_number,
               optional($arr_of_licences[$i]->latest_lodgment_date)->format('d-m-Y'),
               is_null($licence_logded) ? 'FALSE': 'TRUE',
               optional($arr_of_licences[$i]->delivered_at)->format('d M Y'),
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
        $spreadsheet->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setWrapText(true);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="temporary_licences_'.now()->format('d_m_y').'.xlsx"');
        header('Cache-Control: max-age=0');        
        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die;
   
}


}
