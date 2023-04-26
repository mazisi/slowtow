<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Actions\LicenceStatus;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ReportFilters\NewAppReportFilter;

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
        

          
            
            $arr_of_licences = (new NewAppReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_licences); $i++ ){

                $notes = (new ExportNotes)->getNoteExports($arr_of_licences[$i]->id, 'Licence');   

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
                       LicenceStatus::getLicenceStatus($arr_of_licences[$i]->status),
                       '',
                       $notes
                    ];

            $arrayData[] = $data;

                }
            (new ExportToSpreadsheet)->exportToExcel('A1:O1', 'New_Apps', $arrayData);
   
}




}
