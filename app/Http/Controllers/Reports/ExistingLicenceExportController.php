<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Actions\LicenceStatus;
use App\Http\Controllers\Reports\ReportFilters\ExistingLicenceReportFilter;
use App\Models\LicenceDate;
use App\Models\LicenceDocument;

class ExistingLicenceExportController extends Controller
{
    public static function export($request){
        $arrayData = array(
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
            
            $arr_of_licences = (new ExistingLicenceReportFilter)->filter($request)->toArray(); 
dd((new ExistingLicenceReportFilter)->filter($request));
            for($i = 0; $i < count($arr_of_licences); $i++ ){
                       
        $data = [ 
                $arr_of_licences[$i]->trading_name,
                getLicenceHolder($arr_of_licences[$i]), 
                $arr_of_licences[$i]->licence_type,
                $arr_of_licences[$i]->licence_number,
                request('boardRegion') ? $arr_of_licences[$i]->province.' - '.$arr_of_licences[$i]->board_region : $arr_of_licences[$i]->province,
                '',
                self::getDate($arr_of_licences[$i]->id,'Activation Fee Requested')? self::getDate($arr_of_licences[$i]->id,'Activation Fee Requested'): '',
                self::getDate($arr_of_licences[$i]->id,'Deposit Paid') ? 'TRUE': 'FALSE',
                self::getDate($arr_of_licences[$i]->id,'Application Lodged (Proof of Lodgement)') ? date('Y/m/d', strtotime(self::getDate($arr_of_licences[$i]->id,'Application Lodged (Proof of Lodgement)'))) : '',
                (new ExistingLicenceExportController)->getProofOfLodgiment($arr_of_licences[$i]->id) ? 'TRUE': 'FALSE',
                self::getDate($arr_of_licences[$i]->id,'Activation Fee Requested')? self::getDate($arr_of_licences[$i]->id,'Activation Fee Requested'): '',
                '',
                self::getDate($arr_of_licences[$i]->id,'Finalisation Paid') ? date('d M Y', strtotime(self::getDate($arr_of_licences[$i]->id,'Finalisation Paid'))) : '',
                self::getDate($arr_of_licences[$i]->id,'Licence Issued') ? date('d M Y', strtotime(self::getDate($arr_of_licences[$i]->id,'Licence Issued'))) : '',
                LicenceStatus::getLicenceStatus($arr_of_licences[$i]->status),
                '',
                ExportNotes::getNoteExports($arr_of_licences[$i]->id, 'Licence')
                ];

             $arrayData[] = $data;          
          }
          (new ExportToSpreadsheet)->exportToExcel('A1:O1', 'Existing_licences_', $arrayData);


}

public static function getDate($licence_id,$stage){
    $getDate = LicenceDate::where('licence_id',$licence_id)->where('stage',$stage)->first();
    return $getDate ? $getDate->dated_at: '';
}

function getProofOfLodgiment($licence_id){
    $proof_of_lodgiment = LicenceDocument::where('licence_id',$licence_id)
                                            ->where('document_type','Application Lodged')->first();
    return $proof_of_lodgiment;

}
}