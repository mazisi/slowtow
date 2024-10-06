<?php

namespace App\Http\Controllers\Reports\Wholesale;

use App\Models\People;
use App\Models\Company;
use App\Actions\ExportNotes;
use App\Models\TransferDate;
use App\Models\TransferDocument;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Actions\ReportShouldHaveStatusInterface;
use App\Http\Controllers\Reports\ReportFilters\WholesaleTransferReportFilter;

class WholesaleTransferExportController extends Controller implements ReportShouldHaveStatusInterface
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
    
            $arr_of_transfers = (new WholesaleTransferReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_transfers); $i++ ){
                       
  
                $data = [         
                    $arr_of_transfers[$i]->trading_name,
                    (new WholesaleTransferExportController)->getTransferHolder($arr_of_transfers[$i]->transfered_to, $arr_of_transfers[$i]),
                    $arr_of_transfers[$i]->province == 'Gauteng' ? $arr_of_transfers[$i]->licence_number : '',
                    request('boardRegion') ? $arr_of_transfers[$i]->province.' - '.$arr_of_transfers[$i]->board_region : $arr_of_transfers[$i]->province,
                    '',
                    '',
                    self::getDate($arr_of_transfers[$i]->id,'Transfer Logded'),
                    (new WholesaleTransferExportController)->getProofOfLodgiment($arr_of_transfers[$i]->id) ? 'TRUE' : 'FALSE',
                    '',
                    self::getDate($arr_of_transfers[$i]->id,'Payment To The Liquor Board'),
                    self::getDate($arr_of_transfers[$i]->id,'Transfer Issued'),
                    (new WholesaleTransferExportController)->getStatus($arr_of_transfers[$i]->status),
                    ExportNotes::getNoteExports($arr_of_transfers[$i]->id, 'Transfer')
                 ];

            $arrayData[] = $data;

                }
                
                (new ExportToSpreadsheet)->exportToExcel('A1:L1', 'Transfers_', $arrayData);
       

            

}



function getStatus($number) : string {
    switch ($number) {
        case '100':
           $status = 'Client Quoted';
            break;
        case '200':
            $status = 'Client Invoiced';
            break;
        case '300':
            $status = 'Client Paid';
            break;
        case '400':
            $status = 'Prepare Transfer Application';
            break;
        case '500':
            $status = 'Payment To The Liquor Board';
            break;
        case '600':
            $status = 'Scanned Application';
            break;
        case '700':
            $status = 'Application Logded';
            break;
        case '800':
            $status = 'Activation Fee Paid';
            break;
        case '900':
            $status = 'Transfer Issued';
            break;
        case '1000':
            $status = 'Transfer Delivered';
            break;
        default:
            $status = '';
            break;
    }
    return $status;
}         


function getProofOfLodgiment($licence_transfer_id){
    $proof_of_lodgiment = TransferDocument::where('licence_transfer_id',$licence_transfer_id)
                                            ->where('doc_type','Transfer Logded')->first(['id']);
    return $proof_of_lodgiment;

}

function getTransferHolder($currentHolder, $transfer) {
    if($currentHolder == 'Company'){
        $company = Company::find($transfer->company_id);
        if(!is_null($company)){
            return $company->name;
        }
        return '';
    }
       $person  = People::find($transfer->people_id);
       if(!is_null($person)){
           return $person->full_name;
       }
       return '';
    
}

public static function getDate($transfer_id,$stage){
    $getDate = TransferDate::where('transfer_id',$transfer_id)->where('stage',$stage)->first();
    return $getDate ? $getDate->dated_at : '';
}

}