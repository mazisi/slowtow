<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\AlterationDocument;
use Illuminate\Support\Facades\DB;
use App\Actions\AlterationExportAction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AllReportsController extends Controller
{
    public static function exportAll($request){

        $alterationData = array(
            array(                
                'TRADING NAME',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DATE LODGED',
                'PROOF OF LODGIMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'COMMENT IF APPLICABLE'
            )
        );
        $arr_of_alterations = [];


        $alterations = DB::table('alterations')
                            ->selectRaw("alterations.id, alterations.certification_issued_at, licences.trading_name, licences.licence_number, licences.province, 
                            licences.licence_issued_at, licences.board_region,alterations.date,alterations.status ")
                            ->join('licences', 'licences.id' , '=', 'alterations.licence_id' )
                                ->when(function($query){
                                    $query->when(request('month_from') && request('month_to'), function($query){
                                        $query->whereBetween(DB::raw('MONTH(alterations.date)'),[request('month_from'), request('month_to')]);
                                     })
                        
                                    ->when(request('month_from') && !request('month_to'), function ($query)  {
                                        $query->whereMonth('alterations.date', request('month_from'));
                                    })
                                    ->when(request('activeStatus') == 'Active', function ($query) {
                                        $query->where('is_licence_active',true);
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
                                'date',
                                'licence_issued_at'
                            ]);
  
                    $status = '';
                    $notesCollection = '';

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
                            $status = 'Null';
                            break;
                        }

                        $notes = Task::where('model_id',$arr_of_alterations[$i]->id)->where('model_type','Alteration')->get(['body','created_at']);

                        $proof_of_logdiment = AlterationDocument::where('alteration_id',$arr_of_alterations[$i]->id)->where('doc_type','Alterations Lodged')->first(['id']);


                        if(!is_null($notes) || !empty($notes)){
                            foreach ($notes as $note) {
                            $notesCollection .=  $note->created_at.' '.$note->body. ' ';
                            }
                        }
                        
                        
                        $data = [
                        $arr_of_alterations[$i]->trading_name, 
                        $arr_of_alterations[$i]->licence_number, 
                        $arr_of_alterations[$i]->province.'/'.$arr_of_alterations[$i]->board_region,
                        $arr_of_alterations[$i]->date,
                        is_null($proof_of_logdiment) ? 'FALSE' : 'TRUE',
                        $arr_of_alterations[$i]->certification_issued_at,
                        $status, 
                        $notesCollection
                        ];

                        $alterationData[] = $data;

                }

                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()
                ->fromArray(
                $alterationData,   // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
                );

        foreach ($spreadsheet->getActiveSheet()->getColumnIterator() as $column) {
        $spreadsheet->getActiveSheet()->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setWrapText(true);



        
        $existingLicences = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Existing');
        $spreadsheet->addSheet($existingLicences, 1);

        $existingLicencesData = array(
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
        $arr_of_existing_licences = [];

        $licences = DB::table('licences')
                 ->selectRaw("licences.id, is_licence_active, trading_name,licence_type_id, licence_types.licence_type, province, licence_number,
                              deposit_paid_at, application_lodged_at, activation_fee_paid_at, client_paid_at,
                              client_paid_at,status, board_region")

                 ->join('licence_types', 'licences.licence_type_id' , '=', 'licence_types.id')

                     ->when($request,function($query){
                        $query->when(request('month_from') && request('month_to'), function($query){
                            $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                         })
               
                         ->when(request('month_from') && !request('month_to'), function ($query){
                            $query->whereMonth('licence_date', request('month_from'));
                        })

                        ->when(request('activeStatus') === 'Active', function ($query) {
                            $query->where('is_licence_active',true);
                        })
                        ->when(request('activeStatus') === 'Inactive', function ($query) {
                            $query->where('is_licence_active',false);
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

                        ->when(request('selectedDates'), function ($query) {
                            //$query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
                         });
                        })
                        ->whereNull('deleted_at')
                        ->where('is_new_app',NULL)
                        ->orWhere('is_new_app',0)
                        ->orderBy('trading_name')
                        ->get([
                            'id',
                            'trading_name',
                            'licence_number',
                            'licence_type_id',
                            'licence_type',
                            'province',
                            'board_region',
                            'deposit_paid_at',
                            'application_lodged_at',
                            'activation_fee_paid_at',
                            'client_paid_at','status',
                            'is_new_app'
                            ]);

            $existing_licences_status = '';
            $notesCollection = '';
            
            $arr_of_existing_licences = $licences->toArray(); 

            for($i = 0; $i < count($arr_of_existing_licences); $i++ ){

                switch ($arr_of_existing_licences[$i]->status) {
                    case '1':
                       $existing_licences_status = 'Client Quoted';
                        break;
                    case '2':
                        $existing_licences_status = 'Deposit Paid';
                        break;
                    case '3':
                        $existing_licences_status = 'Client Invoiced';
                        break;
                    case '4':
                        $existing_licences_status = 'Prepare New Application';
                        break;
                    case '5':
                        $existing_licences_status = 'Payment To The Liquor Board';
                        break;
                    case '6':
                        $existing_licences_status = 'Scanned Application';
                        break;
                    case '7':
                        $existing_licences_status = 'Application Lodged';
                        break;
                    case '8':
                        $existing_licences_status = 'Initial Inspection';
                        break;
                    case '9':
                        $existing_licences_status = 'Liquor Board Requests';
                        break;
                    case '10':
                        $existing_licences_status = 'Final Inspection';
                        break;
                    case '11':
                        $existing_licences_status = 'Activation Fee Requested';
                        break;
                    case '12':
                        $existing_licences_status = 'Client Finalisation Invoice';
                        break;
                    case '13':
                        $existing_licences_status = 'Client Paid';
                        break;
                    case '14':
                        $existing_licences_status = 'Activation Fee Paid';
                        break;
                    case '15':
                        $existing_licences_status = 'Licence Issued';
                        break;
                    case '16':
                        $existing_licences_status = 'Licence Delivered';
                        break;
                    default:
                        $existing_licences_status='Null';
                        break;
                    }

                    $notes = Task::where('model_id',$arr_of_existing_licences[$i]->id)->where('model_type','Licence')->get(['body','created_at']);
                    //check if client has been logded
                    $is_client_logded = LicenceDocument::where('licence_id',$arr_of_existing_licences[$i]->id)->where('document_type','Application Lodged')->first(['document_name']);
    
                    if(!is_null($notes) || !empty($notes)){
                        foreach ($notes as $note) {
                            $notesCollection .=  $note->created_at.' '.$note->body. ' ';
                        }
                    }

               $data = [ 
                       $arr_of_existing_licences[$i]->trading_name, 
                       $arr_of_existing_licences[$i]->licence_type,
                       $arr_of_existing_licences[$i]->licence_number,
                       $arr_of_existing_licences[$i]->province,
                       'NULL',
                       is_null($arr_of_existing_licences[$i]->deposit_paid_at) ? 'FALSE': 'TRUE',
                       optional($arr_of_existing_licences[$i]->application_lodged_at)->format('d M Y'),
                       is_null($is_client_logded) ? 'FALSE': 'TRUE',
                       $arr_of_existing_licences[$i]->activation_fee_paid_at,
                       'NULL',
                       optional($arr_of_existing_licences[$i]->client_paid_at)->format('d M Y'),
                       'NULL',
                       $existing_licences_status,
                       'NULL',
                       $notesCollection
                    ];

                $existingLicencesData[] = $data;

                }
                $spreadsheet->setActiveSheetIndex(1)
                ->fromArray(
                $existingLicencesData,
                NULL, 
                'A1'  
                );

                foreach ($spreadsheet->setActiveSheetIndex(1)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(1)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                $spreadsheet->setActiveSheetIndex(1)->getStyle('A1:O1')->getFont()->setBold(true);
                $spreadsheet->setActiveSheetIndex(1)->getStyle('A1:O1')->getAlignment()->setWrapText(true);

        
        
        







        $newApplications = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'New Apps');
        $spreadsheet->addSheet($newApplications, 2);
        $newAppsData = array(
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
        $arr_of_new_apps_licences = [];

        $new_app_licences = DB::table('licences')
                 ->selectRaw("licences.id, is_licence_active, trading_name,licence_type_id, licence_types.licence_type, province, licence_number,
                              deposit_paid_at, application_lodged_at, activation_fee_paid_at, client_paid_at,
                              client_paid_at,status, board_region")

                 ->join('licence_types', 'licences.licence_type_id' , '=', 'licence_types.id')

                     ->when($request,function($query){
                        $query->when(request('month_from') && request('month_to'), function($query){
                            $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                         })
               
                         ->when(request('month_from') && !request('month_to'), function ($query)  {
                            $query->whereMonth('licence_date', request('month_from'));
                        })

                        ->when(request('activeStatus') === 'Active', function ($query) {
                            $query->where('is_licence_active',true);
                        })
                        ->when(request('activeStatus') === 'Inactive', function ($query) {
                            $query->where('is_licence_active',false);
                        })

                        ->when(!empty(request('province')), function ($query) {
                            $query->whereIn('province',array_values(explode(",",request('province'))));
                        })
                        ->when(!empty(request('boardRegion')), function ($query) {
                            $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
                        })
                        ->when(request('new_app_stages'), function ($query) {
                            $query->whereIn('status', array_values(explode(",",request('new_app_stages'))));
                        })
                        ->when(!empty(request('applicant')), function ($query) {
                            $query->where('belongs_to',request('applicant'));
                        })
                        ->when(!empty(request('licence_types')), function ($query) {
                            $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
                        })

                        ->when(!empty(request('selectedDates')), function ($query) {
                            //$query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
                         });
                        })
                        ->whereNull('deleted_at')
                        ->where('is_new_app',true)
                        ->orWhere('is_new_app',1)
                        ->orderBy('trading_name')
                        ->get([
                            'id',
                            'trading_name',
                            'licence_number',
                            'licence_type_id',
                            'licence_type',
                            'board_region',
                            'province',
                            'deposit_paid_at',
                             'application_lodged_at',
                             'activation_fee_paid_at',
                             'client_paid_at','status','is_new_app'
                            ]);

    $newAppsStatus = '';
    $newAppNotesCollection = '';

    $arr_of_new_apps_licences = $new_app_licences->toArray(); 

    for($i = 0; $i < count($arr_of_new_apps_licences); $i++ ){

        switch ($arr_of_new_apps_licences[$i]->status) {
            case '1':
               $newAppsStatus = 'Client Quoted';
                break;
            case '2':
                $newAppsStatus = 'Deposit Paid';
                break;
            case '3':
                $newAppsStatus = 'Client Invoiced';
                break;
            case '4':
                $newAppsStatus = 'Prepare New Application';
                break;
            case '5':
                $newAppsStatus = 'Payment To The Liquor Board';
                break;
            case '6':
                $newAppsStatus = 'Scanned Application';
                break;
            case '7':
                $newAppsStatus = 'Application Lodged';
                break;
            case '8':
                $newAppsStatus = 'Initial Inspection';
                break;
            case '9':
                $newAppsStatus = 'Liquor Board Requests';
                break;
            case '10':
                $newAppsStatus = 'Final Inspection';
                break;
            case '11':
                $newAppsStatus = 'Activation Fee Requested';
                break;
            case '12':
                $newAppsStatus = 'Client Finalisation Invoice';
                break;
            case '13':
                $newAppsStatus = 'Client Paid';
                break;
            case '14':
                $newAppsStatus = 'Activation Fee Paid';
                break;
            case '15':
                $newAppsStatus = 'Licence Issued';
                break;
            case '16':
                $newAppsStatus = 'Licence Delivered';
                break;
            default:
                $newAppsStatus='Null';
                break;
            }

            $new_app_notes = Task::where('model_id',$arr_of_new_apps_licences[$i]->id)->where('model_type','Licence')->get(['body','created_at']);
            //check if client has been logded
            $is_client_logded_new_app = LicenceDocument::where('licence_id',$arr_of_new_apps_licences[$i]->id)->where('document_type','Application Lodged')->first(['document_name']);

            if(!is_null($new_app_notes) || !empty($new_app_notes)){
                foreach ($new_app_notes as $note) {
                    $newAppNotesCollection .=  $note->created_at.' '.$note->body. ' ';
                }
            }

       $data = [ 
               $arr_of_new_apps_licences[$i]->trading_name, 
               $arr_of_new_apps_licences[$i]->licence_type,
               $arr_of_new_apps_licences[$i]->licence_number,
               $arr_of_new_apps_licences[$i]->province,
               'NULL',
               is_null($arr_of_new_apps_licences[$i]->deposit_paid_at) ? 'FALSE': 'TRUE',
               $arr_of_new_apps_licences[$i]->application_lodged_at,
               is_null($is_client_logded_new_app) ? 'FALSE': 'TRUE',
               $arr_of_new_apps_licences[$i]->activation_fee_paid_at,
               'NULL',
               $arr_of_new_apps_licences[$i]->client_paid_at,
               'NULL',
               $newAppsStatus,
               'NULL',
               $newAppNotesCollection
            ];

            $newAppsData[] = $data;

            }
            $spreadsheet->setActiveSheetIndex(2)
             ->fromArray(
             $newAppsData,
             NULL, 
             'A1'  
             );
 
             foreach ($spreadsheet->setActiveSheetIndex(2)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(2)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }
    
        $spreadsheet->setActiveSheetIndex(2)->getStyle('A1:O1')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(2)->getStyle('A1:O1')->getAlignment()->setWrapText(true);








        $nominations = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Nominations');
        $spreadsheet->addSheet($nominations, 3);

        $renewals = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Renewals');
        $spreadsheet->addSheet($renewals, 4);

        $temporary_apps = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Temporary Apps');
        $spreadsheet->addSheet($temporary_apps, 5);

        $transfers = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Transfers');
        $spreadsheet->addSheet($transfers, 6);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="all_apps_'.now()->format('d_m_y').'.xlsx"');
        header('Cache-Control: max-age=0');        
        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die;
      
    }
}
