<?php

namespace App\Http\Controllers\Reports\Wholesale;

use App\Actions\ExportNotes;
use App\Models\AlterationDate;
use App\Models\AlterationDocument;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Actions\ReportShouldHaveStatusInterface;
use App\Http\Controllers\Reports\ReportFilters\AlterationFilter;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleAlterationFilter;

class WholesaleAlterationExportController extends Controller implements ReportShouldHaveStatusInterface
{
    public static function export($request){
        $arrayData = array(
            array(                
                'TRADING NAME',
                'LICENCE HOLDER',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DATE LODGED',
                'PROOF OF LODGEMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'COMMENT IF APPLICABLE'
            )
        );
               $arr_of_alterations = (new WholesaleAlterationFilter)->filter($request)->toArray(); 

                for($i = 0; $i < count($arr_of_alterations); $i++ ){
                    $status = (new WholesaleAlterationExportController)->getStatus($arr_of_alterations[$i]->status);
                    $proof_of_lodgiment = (new WholesaleAlterationExportController)->getProofOfLodgiment($arr_of_alterations[$i]->id);                            
                        
                        $data = [
                        $arr_of_alterations[$i]->trading_name,
                        getLicenceHolder($arr_of_alterations[$i]), 
                        $arr_of_alterations[$i]->licence_number, 
                        request('boardRegion') ? $arr_of_alterations[$i]->province: '',
                        self::getDate($arr_of_alterations[$i]->id,'NLA 14 Application Lodged')? self::getDate($arr_of_alterations[$i]->id,'NLA 14 Application Lodged'): '',
                        $proof_of_lodgiment ? 'TRUE' : 'FALSE',
                        self::getDate($arr_of_alterations[$i]->id,'NLA 15 Certificate Issued'),
                        $status, 
                        ExportNotes::getNoteExports($arr_of_alterations[$i]->id, 'Alteration'),
                        ];
                        $arrayData[] = $data;

                }
                  (new ExportToSpreadsheet)->exportToExcel('A1:H1', 'Alterations_', $arrayData);            
   
   
        }

        function getProofOfLodgiment($alteration_id){
            $proof_of_lodgiment = AlterationDocument::where('alteration_id',$alteration_id)
                                                    ->where('doc_type','NLA 14 Application Lodged')->first(['id']);
            return $proof_of_lodgiment;

        }

        public static function getDate($alteration_id,$stage){
            $getDate = AlterationDate::where('alteration_id',$alteration_id)->where('stage',$stage)->first();
            return $getDate ? $getDate->dated_at : '';
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
                    $status = 'Prepare NLA 14 Application';
                    break;
                case '500':
                    $status = 'Payment to the National Liquor Authority';
                    break;
                case '600':
                    $status = 'NLA 14 Application Lodged';
                    break;
                case '700':
                    $status = 'Additional Documents Requested';
                    break;
                case '800':
                    $status = 'NLA 15 Certificate Issued';
                    break;
                case '900':
                    $status = 'NLA 15 Certificate Delivered';
                    break;
                default:
                    $status = '';
                    break;
                }
            return $status;
        }


}