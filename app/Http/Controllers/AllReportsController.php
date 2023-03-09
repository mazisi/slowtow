<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\RenewalDocument;
use App\Models\AlterationDocument;
use Illuminate\Support\Facades\DB;
use App\Models\TemporalLicenceDocument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AllReportsController extends Controller
{
    public static function exportAll($request){dd($request);

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
                                    })
                                    ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                                        $query->where('alterations.status','<', 8)
                                        ->orWhere('alterations.status', 0)
                                        ->orWhereNull('alterations.status');
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
                $spreadsheet->getActiveSheet()->setTitle("Alterations")
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
                         })

                         ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                            $query->where('status','<', 16)
                            ->orWhere('status', 0)
                            ->orWhereNull('status');
                        })
    
                        ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                            $query->where('status',16);
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
                    $is_existing_licence_client_logded = LicenceDocument::where('licence_id',$arr_of_existing_licences[$i]->id)->where('document_type','Application Lodged')->first(['document_name']);
    
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
                       is_null($is_existing_licence_client_logded) ? 'FALSE': 'TRUE',
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
                         })
                         ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                            $query->where('status','<', 16)
                            ->orWhere('status', 0)
                            ->orWhereNull('status');
                        })
    
                         ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                            $query->where('status',16);
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







//  <==============================================Start OF Nomination Export===============================================================->

        $nominations = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Nominations');
        $spreadsheet->addSheet($nominations, 3);




        $arrayNominationData = array(
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
                            nominations.status, nominations.nomination_issued_at,board_region")
                        ->join('nomination_people', 'nomination_people.nomination_id' , '=', 'nominations.id' )
                        ->join('people', 'people.id' , '=', 'nomination_people.people_id' )
                        ->join('licences', 'licences.id' , '=', 'nominations.licence_id' )
                            ->when(function($query){
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
                                    $query->whereNull('is_licence_active');
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

                            })->when(request('selectedDates'), function ($query) {
                                  $query->whereIn('year',array_values(explode(",",request('selectedDates'))));
                            })
                            ->when(request('nomination_stages'), function ($query) {
                                $query->whereIn('nominations.status',array_values(explode(",",request('nomination_stages'))));
                          })

                          ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                            $query->where('nominations.status','<', 10)
                            ->orWhere('nominations.status', 0)
                            ->orWhereNull('nominations.status');
                        })
    
                        ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                            $query->where('nominations.status',10);
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
                                'status'
                            ]);
    
                        $nom_status = '';
                        $nomNotesCollection = '';
            
                        $arr_of_nominations = $nominations->toArray(); 
    
    for($i = 0; $i < count($arr_of_nominations); $i++ ){
        switch ($arr_of_nominations[$i]->status) {
            case '1':
                $nom_status = 'Client Quoted';
                 break;
             case '2':
                 $nom_status = 'Client Invoiced';
                 break;
             case '3':
                 $nom_status = 'Client Paid';
                 break;
             case '4':
                 $nom_status = 'Payment To The Liquor Board';
                 break;
             case '5':
                 $nom_status = 'Select Nominees';
                 break;
             case '6':
                 $nom_status = 'Prepare Nomination Application';
                 break;
             case '7':
                 $nom_status = 'Scanned Application';
                 break;
             case '8':
                 $nom_status = 'Nomination Lodged';
                 break;
             case '9':
                 $nom_status = 'Nomination Issued';
                 break;
             case '10':
                 $nom_status = 'Nomination Delivered';
                 break;
             default:
                 $nom_status = 'Null';
                 break;
        }

        $nomNotes = Task::where('model_id',$arr_of_nominations[$i]->id)->where('model_type','Nomination')->get(['body','created_at']);

        // $is_client_paid = NominationDocument::where('nomination_id',$arr_of_nominations[$i]->id)->where('doc_type','Payment To The Liquor Board')->first();

    
            if(!is_null($nomNotes) || !empty($nomNotes)){
                foreach ($nomNotes as $nomNote) {
                    $nomNotesCollection .=  $nomNote->created_at.' '.$nomNote->body. ' ';
                }
            }

    $data = [ 
               $arr_of_nominations[$i]->trading_name, 
               $arr_of_nominations[$i]->full_name, 
               $arr_of_nominations[$i]->licence_number, 
               $arr_of_nominations[$i]->province.'/'.$arr_of_nominations[$i]->board_region,
               'NULL',
               $arr_of_nominations[$i]->payment_to_liquor_board_at,
               $arr_of_nominations[$i]->nomination_lodged_at,
               (is_null($arr_of_nominations[$i]->nomination_lodged_at)) ? 'FALSE' : 'TRUE',
               $arr_of_nominations[$i]->nomination_issued_at,
               $nom_status,
               $nomNotesCollection
               ];

               $arrayNominationData[] = $data;

        }

        $spreadsheet->setActiveSheetIndex(3)
         ->fromArray(
         $arrayNominationData,
         NULL,
         'A1'
         );

         foreach ($spreadsheet->setActiveSheetIndex(3)->getColumnIterator() as $column) {
            $spreadsheet->setActiveSheetIndex(3)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(3)->getStyle('A1:O1')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(3)->getStyle('A1:O1')->getAlignment()->setWrapText(true);

        //  <=================================================End OF Nomination Export=====================================================->



        // <===================================================Renewals Export Start=====================================================>
        $renewals = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Renewals');
        $spreadsheet->addSheet($renewals, 4);



        $arrayRenewalData = array(
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
                    ->selectRaw("licence_renewals.id, is_licence_active, trading_name, board_region,licence_number, licence_renewals.date, 
                                 licence_renewals.client_paid_at,licence_renewals.status, payment_to_liquor_board_at, renewal_issued_at, renewal_delivered_at,
                                 is_quote_sent")

                    ->join('licences', 'licences.id' , '=', 'licence_renewals.licence_id')

                        ->when($request,function($query){
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
                                // $query->whereIn(DB::raw('licences.board_region'),array_values(explode(",",request('boardRegion'))));
                                $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
                            })
                            ->when(request('applicant'), function ($query)  {
                                $query->where('belongs_to',request('applicant'));
                            })
                            ->when(request('year'), function ($query) {
                                $query->whereIn('date',array_values(explode(",",request('year'))));
                            })

                            ->when(request('renewal_stages'), function ($query) {
                                $query->whereIn('licence_renewals.status',array_values(explode(",",request('renewal_stages'))));
                            })
                            ->when(request('licence_types'), function ($query)  {
                                $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
                            })
                            ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                                $query->where('licence_renewals.status','<', 6)
                                ->orWhere('licence_renewals.status', 0)
                                ->orWhereNull('licence_renewals.status');
                            })

                            ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                                $query->where('licence_renewals.status',6);
                            });

                            })->whereNull('licences.deleted_at')->whereNull('licence_renewals.deleted_at')
                            ->orderBy('trading_name')
                            ->get([
                                'id',
                                'board_region',
                                'is_licence_active',
                                'trading_name',
                                'licence_number',
                                'date',
                                'is_quote_sent',
                                'client_paid_at',
                                'payment_to_liquor_board_at',
                                'renewal_issued_at',
                                'renewal_delivered_at',
                            ]);

       
            $renewalsNotesCollection = '';
            
            $arr_of_renewals = $renewals->toArray(); 

            for($i = 0; $i < count($arr_of_renewals); $i++ ){

                $renewalNotes = Task::where('model_id',$arr_of_renewals[$i]->id)->where('model_type','Licence Renewal')->get(['body','created_at']);

                //check if client has been quoted
             $is_renewal_quoted = RenewalDocument::where('licence_renewal_id',$arr_of_renewals[$i]->id)->where('doc_type','Client Quoted')->first(['id']);
    
                    if(!is_null($renewalNotes) || !empty($renewalNotes)){
                        foreach ($renewalNotes as $nominationNote) {
                            $renewalsNotesCollection .=  $nominationNote->created_at.' '.$nominationNote->body. ' ';
                        }
                    }
                   
                   
                
            $data = [ 
                       $arr_of_renewals[$i]->is_licence_active ? 'A' : 'D',
                       $arr_of_renewals[$i]->trading_name, 
                       $arr_of_renewals[$i]->licence_number,
                       $arr_of_renewals[$i]->date,
                       'NULL',
                       is_null($is_renewal_quoted) ? 'FALSE' : 'TRUE',
                       is_null($arr_of_renewals[$i]->is_quote_sent) ? 'FALSE' : 'TRUE',
                       $arr_of_renewals[$i]->client_paid_at,
                       'NULL',
                       $arr_of_renewals[$i]->payment_to_liquor_board_at,
                       $arr_of_renewals[$i]->renewal_issued_at,
                       $arr_of_renewals[$i]->renewal_delivered_at,
                       'NULL',
                       $renewalsNotesCollection
                    ];

               $arrayRenewalData[] = $data;

                }
                
                $spreadsheet->setActiveSheetIndex(4)
                    ->fromArray(
                    $arrayRenewalData,
                    NULL,
                    'A1'
                    );

                foreach ($spreadsheet->setActiveSheetIndex(4)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(4)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                    $spreadsheet->setActiveSheetIndex(4)->getStyle('A1:L1')->getFont()->setBold(true);
                    $spreadsheet->setActiveSheetIndex(4)->getStyle('A1:L1')->getAlignment()->setWrapText(true);
// <=================================End OF Renewals Export===============================================================>



// <======================================Temporary Exports Begin=================================================================>
        $temporary_apps = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Temporary Apps');
        $spreadsheet->addSheet($temporary_apps, 5);

        $arr_of_licences = [];
        $arrayTempData = array(
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
                          })->whereNull('temporal_licences.deleted_at')
                          ->orderBy('event_name')
                          ->get([
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

    $temp_status = '';
    $applicant = '';
    $tempNotesCollection = '';

    $arr_of_licences = $merged_data->toArray(); 

    for($i = 0; $i < count($arr_of_licences); $i++ ){

        switch ($arr_of_licences[$i]->status) {
            case '1':
               $temp_status = 'Client Quoted';
                break;
            case '2':
                $temp_status = 'Client Invoiced';
                break;
            case '3':
                $temp_status = 'Client Paid';
                break;
            case '4':
                $temp_status = 'Prepare Temporary Application';
                break;
            case '5':
                $temp_status = 'Payment To The Liquor Board';
                break;
            case '6':
                $temp_status = 'Scanned Application';
                break;
            case '7':
                $temp_status = 'Temporary Licence Lodged ';
                break;
            case '8':
                $temp_status = 'Temporary Licence Issued ';
                break;
            case '9':
                $temp_status = 'Temporary Licence Delivered';
                break;
           
            default:
                $temp_status = 'NULL';
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

    $temp_notes = Task::where('model_id',$arr_of_licences[$i]->id)->where('model_type','Temporal Licence')->get(['body','created_at']);
   
    $get_temp_invoice_number = TemporalLicenceDocument::where('temporal_licence_id',$arr_of_licences[$i]->id)->where('doc_type','Client Invoiced')->first(['document_name']);
    $temp_licence_logded = TemporalLicenceDocument::where('temporal_licence_id',$arr_of_licences[$i]->id)->where('doc_type','Licence Lodged')->first(['id']);

            if(!is_null($temp_notes) || !empty($temp_notes)){
                foreach ($temp_notes as $temp_note) {
                    $tempNotesCollection .=  $temp_note->created_at.' '.$temp_note->body. ' ';
                }
            }

       $data = [ 
               $arr_of_licences[$i]->event_name, 
               $applicant,
               date('d-m-Y', strtotime($arr_of_licences[$i]->start_date)). ' - '.date('d-m-Y', strtotime($arr_of_licences[$i]->end_date)),
               'NULL',
               $arr_of_licences[$i]->address,
               is_null($get_temp_invoice_number) ? '' : $get_temp_invoice_number->document_name,
               $arr_of_licences[$i]->client_paid_at,
               $arr_of_licences[$i]->liquor_licence_number,
               optional($arr_of_licences[$i]->latest_lodgment_date)->format('d-m-Y'),
               is_null($temp_licence_logded) ? 'FALSE': 'TRUE',
               optional($arr_of_licences[$i]->delivered_at)->format('d M Y'),
               $temp_status,
               $tempNotesCollection
            ];

    $arrayTempData[] = $data;

        }
        
            $spreadsheet->setActiveSheetIndex(5)
             ->fromArray(
             $arrayTempData,   // The data to set
             NULL,        // Array values with this value will not be set
             'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
             );

             foreach ($spreadsheet->setActiveSheetIndex(5)->getColumnIterator() as $column) {
                $spreadsheet->setActiveSheetIndex(5)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }

                $spreadsheet->setActiveSheetIndex(5)->getStyle('A1:L1')->getFont()->setBold(true);
                $spreadsheet->setActiveSheetIndex(5)->getStyle('A1:L1')->getAlignment()->setWrapText(true);
// <=================================End OF Temp Export===============================================================>
        

        $transfers = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Transfers');
        $spreadsheet->addSheet($transfers, 6);

        $arrayTransferData = array(
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
                                     board_region,issued_at, delivered_at,province")
    
                        ->join('licences', 'licences.id' , '=', 'licence_transfers.licence_id')
    
                        ->when(function($query){
                            $query->when(request('month_from') && request('month_to'), function($query){
                                $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
                            })
                  
                            ->when(request('month_from') && !request('month_to'), function ($query)  {
                                $query->whereMonth('licence_date', request('month_from'));
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
                                $query->where('is_licence_active', true);
                            })
    
                            ->when(request('licence_types'), function ($query)  {
                                $query->where('licence_type_id',request('licence_types'));
                            })

                            ->when(request('is_licence_complete') === 'Outstanding', function ($query) {
                                $query->where('licence_transfers.status','<', 10)
                                ->orWhere('licence_transfers.status', 0)
                                ->orWhereNull('licence_transfers.status');
                            })
        
                            ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                                $query->where('licence_transfers.status',10);
                            });
    
                        })->when(request('selectedDates'), function ($query) {
                              $query->whereIn('year',array_values(explode(",",request('selectedDates'))));
                        })
                        ->when(request('transfer_stages'), function ($query) {
                            $query->whereIn('licence_transfers.status', array_values(explode(",",request('transfer_stages'))));
                        })
                        ->whereNull('licences.deleted_at')->whereNull('licence_transfers.deleted_at')
                        ->orderBy('trading_name')->get([
                            'trading_name',
                            'province',
                            'board_region',
                            'lodged_at',
                            'payment_to_liquor_board_at',
                            'issued_at',
                            'status'
                        ]);
    
                                 
                $transfer_status = '';
                $transferNotesCollection = '';
    
                $arr_of_transfers = $transfers->toArray(); 
    
                for($i = 0; $i < count($arr_of_transfers); $i++ ){
                    switch ($arr_of_transfers[$i]->status) {
                        case '1':
                           $transfer_status = 'Client Quoted';
                            break;
                        case '2':
                            $transfer_status = 'Client Invoiced';
                            break;
                        case '3':
                            $transfer_status = 'Client Paid';
                            break;
                        case '4':
                            $transfer_status = 'Prepare Transfer Application';
                            break;
                        case '5':
                            $transfer_status = 'Payment To The Liquor Board';
                            break;
                        case '6':
                            $transfer_status = 'Scanned Application';
                            break;
                        case '7':
                            $transfer_status = 'Application Logded';
                            break;
                        case '8':
                            $transfer_status = 'Activation Fee Paid';
                            break;
                        case '9':
                            $transfer_status = 'Transfer Issued';
                            break;
                        case '10':
                            $transfer_status = 'Transfer Delivered';
                            break;
                        default:
                            $transfer_status = 'Null';
                            break;
                    }
    
                    $transfer_notes = Task::where('model_id',$arr_of_transfers[$i]->id)->where('model_type','Transfer')->get(['body','created_at']);
    
                
                        if(!is_null($transfer_notes) || !empty($transfer_notes)){
                            foreach ($transfer_notes as $transfer_note) {
                                $transferNotesCollection .=  $transfer_note->created_at.' '.$transfer_note->body. ' ';
                            }
                        }
      
                $data = [         
                           $arr_of_transfers[$i]->trading_name, 
                           'NULL',
                           $arr_of_transfers[$i]->province.'/'.$arr_of_transfers[$i]->board_region,
                           'NULL',
                           'NULL',
                           $arr_of_transfers[$i]->lodged_at,
                           (is_null($arr_of_transfers[$i]->lodged_at)) ? 'FALSE' : 'TRUE',
                           'NULL',
                           $arr_of_transfers[$i]->payment_to_liquor_board_at,
                           $arr_of_transfers[$i]->issued_at,
                           $transfer_status,
                           $notesCollection
                        ];
    
                $arrayTransferData[] = $data;
    
                    }
    
           
                $spreadsheet->setActiveSheetIndex(6)
                 ->fromArray(
                 $arrayTransferData,   // The data to set
                 NULL,        // Array values with this value will not be set
                 'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
                 );
     
        foreach ($spreadsheet->setActiveSheetIndex(6)->getColumnIterator() as $column) {
                     $spreadsheet->setActiveSheetIndex(6)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                     
                  }
         
         $spreadsheet->setActiveSheetIndex(6)->getStyle('A1:L1')->getFont()->setBold(true);
         $spreadsheet->setActiveSheetIndex(6)->getStyle('A1:L1')
         ->getAlignment()->setWrapText(true);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="All_Apps_'.now()->format('d_m_y').'.xlsx"');
        header('Cache-Control: max-age=0');        
        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die;
      
    }
}
