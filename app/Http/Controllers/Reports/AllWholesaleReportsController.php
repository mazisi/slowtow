<?php

namespace App\Http\Controllers\Reports;


use App\Models\Report;
use App\Actions\ExportNotes;
use Illuminate\Http\Request;
use App\Actions\LicenceStatus;
use App\Http\Controllers\Controller;
use App\Actions\WholesaLeLicenceStatus;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\Reports\ReportFilters\NewAppReportFilter;
use App\Http\Controllers\Reports\ReportFilters\TemporalExportReportFilter;
use App\Http\Controllers\Reports\Wholesale\WholesaleNewAppExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleRenewalExportController;
use App\Http\Controllers\Reports\Wholesale\WholesaleTransferExportController;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleAlterationFilter;
use App\Http\Controllers\Reports\Wholesale\WholesaleExistingLicenceExportController;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleNewAppReportFilter;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleRenewalReportFilter;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleTransferReportFilter;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleExistingLicenceReportFilter;

class AllWholesaleReportsController extends Controller
{
    
    public static function exportAll($request){
        ini_set('memory_limit', '-1');
        $alterationData = array(
            array(                
                'TRADING NAME',
                'LICENCE HOLDER',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DATE LODGED',
                'PROOF OF LODGIMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'COMMENT IF APPLICABLE'
            )
        );
      
               $arr_of_alterations = (new WholesaleAlterationFilter)->filter($request)->toArray(); 
               

                for($i = 0; $i < count($arr_of_alterations); $i++ ){
                                            
                        $proof_of_lodgiment = (new WholesaleAlterationExportController)->getProofOfLodgiment($arr_of_alterations[$i]->id);
                        //get alteration notes
                                                 
                        
                        $data = [
                            $arr_of_alterations[$i]->trading_name, 
                            getLicenceHolder($arr_of_alterations[$i]),
                            $arr_of_alterations[$i]->licence_number, 
                            request('boardRegion') ? $arr_of_alterations[$i]->province.'-'.$arr_of_alterations[$i]->board_region : $arr_of_alterations[$i]->province,
                            WholesaleAlterationExportController::getDate($arr_of_alterations[$i]->id,'Alterations Lodged'),
                            $proof_of_lodgiment ? 'TRUE' : 'FALSE',
                            WholesaleAlterationExportController::getDate($arr_of_alterations[$i]->id,'Alterations Certificate Issued'),
                            (new WholesaleAlterationExportController)->getStatus($arr_of_alterations[$i]->status), 
                            ExportNotes::getNoteExports($arr_of_alterations[$i]->id, 'Alteration')
                            ];

                        $alterationData[] = $data;

                }
                
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()->setTitle("Alterations")
                ->fromArray(
                $alterationData,
                NULL,
                'A1'  
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
            'LICENCE HOLDER',
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
            
            $arr_of_existing_licences = (new WholesaleExistingLicenceReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_existing_licences); $i++ ){
                
                     $data = [ 
                        $arr_of_existing_licences[$i]->trading_name,
                        getLicenceHolder($arr_of_existing_licences[$i]), 
                        $arr_of_existing_licences[$i]->licence_type,
                        $arr_of_existing_licences[$i]->licence_number,
                        request('boardRegion') ? $arr_of_existing_licences[$i]->province.' - '.$arr_of_existing_licences[$i]->board_region : $arr_of_existing_licences[$i]->province,
                        '',
                        WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Activation Fee Requested')?WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Activation Fee Requested'): '',
                        WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Deposit Paid') ? 'TRUE': 'FALSE',
                        WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Application Lodged') ? date('Y/m/d', strtotime(WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Application Lodged'))) : '',
                        (new WholesaleExistingLicenceExportController)->getProofOfLodgiment($arr_of_existing_licences[$i]->id) ? 'TRUE': 'FALSE',
                        WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Activation Fee Requested')?WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Activation Fee Requested'): '',
                        '',
                        WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Finalisation Paid') ? date('d M Y', strtotime(WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Finalisation Paid'))) : '',
                        WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Licence Issued') ? date('d M Y', strtotime(WholesaleExistingLicenceExportController::getDate($arr_of_existing_licences[$i]->id,'Licence Issued'))) : '',
                        LicenceStatus::getLicenceStatus($arr_of_existing_licences[$i]->status),
                        '',
                        ExportNotes::getNoteExports($arr_of_existing_licences[$i]->id, 'Licence')
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
                'LICENCE HOLDER',
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
        

        $arr_of_new_apps_licences = (new WholesaleNewAppReportFilter)->filter($request)->toArray(); 
   
    for($i = 0; $i < count($arr_of_new_apps_licences); $i++ ){
       

             $data = [ 
                $arr_of_new_apps_licences[$i]->trading_name,
                getLicenceHolder($arr_of_new_apps_licences[$i]), 
                $arr_of_new_apps_licences[$i]->licence_type,
                $arr_of_new_apps_licences[$i]->licence_number,
                request('boardRegion') ? $arr_of_new_apps_licences[$i]->province.' - '.$arr_of_new_apps_licences[$i]->board_region : $arr_of_new_apps_licences[$i]->province,
                '',
                WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Deposit Paid') ? 'TRUE': 'FALSE',
                WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Application Lodged') ? date('Y/m/d', strtotime(WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Application Lodged'))) : '',
                WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Application Lodged') ? 'TRUE': 'FALSE',
                WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Activation Fee Requested') ? WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Activation Fee Requested'): '',
                 '',
                WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Finalisation Paid') ? date('d M Y', strtotime(WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Finalisation Paid'))) : '',
                WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Licence Issued') ? date('d M Y', strtotime(WholesaleNewAppExportController::getDate($arr_of_new_apps_licences[$i]->id,'Licence Issued'))) : '',
                WholesaLeLicenceStatus::getLicenceStatus($arr_of_new_apps_licences[$i]->status),
                '',
                ExportNotes::getNoteExports($arr_of_new_apps_licences[$i]->id, 'Licence')
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




        // <===================================================Renewals Export Start=====================================================>
        $renewals = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Renewals');
        $spreadsheet->addSheet($renewals, 4);



        $arrayRenewalData = array(
            array(
                'ACTIVE/DEACTIVE',
                'TRADING NAME',
                'LICENCE HOLDER',
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
        
            $arr_of_renewals = (new WholesaleRenewalReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_renewals); $i++ ){

                    $data = [ 
                        $arr_of_renewals[$i]->is_licence_active ? 'A' : 'D',
                        $arr_of_renewals[$i]->trading_name,
                        getLicenceHolder($arr_of_renewals[$i]), 
                        $arr_of_renewals[$i]->licence_number,
                        $arr_of_renewals[$i]->date,
                        '',
                        (new WholesaleRenewalExportController)->is_client_quoted($arr_of_renewals[$i]->id) ? 'TRUE' : 'FALSE',
                        $arr_of_renewals[$i]->is_quote_sent ? 'TRUE' : 'FALSE',
                        WholesaleRenewalExportController::getDate($arr_of_renewals[$i]->id,'Client Paid'),
                        '',
                        WholesaleRenewalExportController::getDate($arr_of_renewals[$i]->id,'Payment To The Liquor Board'),
                        WholesaleRenewalExportController::getDate($arr_of_renewals[$i]->id,'Renewal Issued'),
                        WholesaleRenewalExportController::getDate($arr_of_renewals[$i]->id,'Renewal Delivered'),
                        WholesaleRenewalExportController::getDate($arr_of_renewals[$i]->id,'Renewal Delivered') ? 'TRUE' : 'FALSE',
                        ExportNotes::getNoteExports($arr_of_renewals[$i]->id, 'Licence Renewal') 
                     ];

               $arrayRenewalData[] = $data;

                }
                
                $spreadsheet->setActiveSheetIndex(3)
                    ->fromArray(
                    $arrayRenewalData,
                    NULL,
                    'A1'
                    );

                foreach ($spreadsheet->setActiveSheetIndex(3)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(3)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                    $spreadsheet->setActiveSheetIndex(3)->getStyle('A1:L1')->getFont()->setBold(true);
                    $spreadsheet->setActiveSheetIndex(3)->getStyle('A1:L1')->getAlignment()->setWrapText(true);
// <=================================End OF Renewals Export===============================================================>



// <======================================Temporary Exports Begin=================================================================>
        $temporary_apps = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Temporary Apps');
        $spreadsheet->addSheet($temporary_apps, 5);

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

    $arr_of_temp_licences = (new TemporalExportReportFilter)->filter($request)->toArray(); 

    for($i = 0; $i < count($arr_of_temp_licences); $i++ ){
                    
            $data = [ 

                $arr_of_temp_licences[$i]->event_name, 
                (new TemporaLExportController)->getApplicant($arr_of_temp_licences[$i]->belongs_to, $arr_of_temp_licences[$i]),
                date('d-m-Y', strtotime($arr_of_temp_licences[$i]->start_date)). ' - '.date('d-m-Y', strtotime($arr_of_temp_licences[$i]->end_date)),
                '',
                $arr_of_temp_licences[$i]->address,
                //$get_invoice_number ? '' : $get_invoice_number->document_name,
                $arr_of_temp_licences[$i]->client_paid_at,
                $arr_of_temp_licences[$i]->liquor_licence_number,
                $arr_of_temp_licences[$i]->logded_at ? date('d M Y', strtotime($arr_of_temp_licences[$i]->logded_at)) : '',
                (new TemporalExportController)->getProofOfLodgiment($arr_of_temp_licences[$i]->id) ? 'TRUE': 'FALSE',
                $arr_of_temp_licences[$i]->issued_at ? date('d M Y', strtotime($arr_of_temp_licences[$i]->issued_at)) : '',
                (new TemporaLExportController)->getStatus($arr_of_temp_licences[$i]->status),
                ExportNotes::getNoteExports($arr_of_temp_licences[$i]->id, 'Temporal Licence')
             ];
 

    $arrayTempData[] = $data;

        }
        
            $spreadsheet->setActiveSheetIndex(4)
             ->fromArray(
             $arrayTempData,   // The data to set
             NULL,        // Array values with this value will not be set
             'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
             );

             foreach ($spreadsheet->setActiveSheetIndex(4)->getColumnIterator() as $column) {
                $spreadsheet->setActiveSheetIndex(4)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }

                $spreadsheet->setActiveSheetIndex(4)->getStyle('A1:L1')->getFont()->setBold(true);
                $spreadsheet->setActiveSheetIndex(4)->getStyle('A1:L1')->getAlignment()->setWrapText(true);
// <=================================End OF Temp Export===============================================================>
        

        $transfers = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Transfers');
        $spreadsheet->addSheet($transfers, 6);

        $arrayTransferData = array(
            array(
                'CURRENT TRADING NAME',
                'LICENCE HOLDER',
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
                
                $arr_of_transfers = (new WholesaleTransferReportFilter)->filter($request)->toArray(); 
    
                for($i = 0; $i < count($arr_of_transfers); $i++ ){                               

                        $data = [         
                            $arr_of_transfers[$i]->trading_name,
                            (new WholesaleTransferExportController)->getTransferHolder($arr_of_transfers[$i]->transfered_to, $arr_of_transfers[$i]),
                            $arr_of_transfers[$i]->province == 'Gauteng' ? $arr_of_transfers[$i]->licence_number : '',
                            request('boardRegion') ? $arr_of_transfers[$i]->province.' - '.$arr_of_transfers[$i]->board_region : $arr_of_transfers[$i]->province,
                            '',
                            '',
                            WholesaleTransferExportController::getDate($arr_of_transfers[$i]->id,'Transfer Logded'),
                            (new WholesaleTransferExportController)->getProofOfLodgiment($arr_of_transfers[$i]->id) ? 'TRUE' : 'FALSE',
                            '',
                            WholesaleTransferExportController::getDate($arr_of_transfers[$i]->id,'Payment To The Liquor Board'),
                            WholesaleTransferExportController::getDate($arr_of_transfers[$i]->id,'Transfer Issued'),
                            (new WholesaleTransferExportController)->getStatus($arr_of_transfers[$i]->status),
                            ExportNotes::getNoteExports($arr_of_transfers[$i]->id, 'Transfer')
                         ];
    
                $arrayTransferData[] = $data;
    
                    }
    
           
                $spreadsheet->setActiveSheetIndex(5)
                 ->fromArray(
                 $arrayTransferData,   // The data to set
                 NULL,        // Array values with this value will not be set
                 'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
                 );
     
        foreach ($spreadsheet->setActiveSheetIndex(5)->getColumnIterator() as $column) {
                     $spreadsheet->setActiveSheetIndex(5)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                     
                  }
         
         $spreadsheet->setActiveSheetIndex(5)->getStyle('A1:L1')->getFont()->setBold(true);
         $spreadsheet->setActiveSheetIndex(5)->getStyle('A1:L1')
         ->getAlignment()->setWrapText(true);

         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename="Wholesale_All_Apps.xlsx"');
         header('Cache-Control: max-age=0');        
         $writer = new Xlsx($spreadsheet);
         $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
         $writer->save('php://output');
         die;
        // $writer->save(storage_path('app/public/Wholesale_All_Apps.Xlsx'));
    }
}