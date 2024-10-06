<?php

namespace App\Http\Controllers\Reports\Wholesale;

use App\Models\RenewalDate;
use App\Actions\ExportNotes;
use App\Models\RenewalDocument;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ReportFilters\Wholesale\WholesaleRenewalReportFilter;

class WholesaleRenewalExportController extends Controller
{

    
    public static function export($request){
        $arrayData = array(
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
        $arr_of_renewals = [];
                    
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
                    self::getDate($arr_of_renewals[$i]->id,'Client Paid'),
                    '',
                    self::getDate($arr_of_renewals[$i]->id,'Payment To The Liquor Board'),
                    self::getDate($arr_of_renewals[$i]->id,'Renewal Issued'),
                    self::getDate($arr_of_renewals[$i]->id,'Renewal Delivered'),
                    self::getDate($arr_of_renewals[$i]->id,'Renewal Delivered') ? 'TRUE' : 'FALSE',
                    ExportNotes::getNoteExports($arr_of_renewals[$i]->id, 'Licence Renewal') 
                 ];

                    $arrayData[] = $data;

                }

           
   
        (new ExportToSpreadsheet)->exportToExcel('A1:L1', 'Renewals_', $arrayData);          
    
           
    }

  
   function is_client_quoted($renewal_id){
    return RenewalDocument::where('licence_renewal_id',$renewal_id)->where('doc_type','Client Quoted')->first(['id']);
   }

 public static function getDate($renewal_id,$stage){
    $getDate = RenewalDate::where('renewal_id',$renewal_id)->where('stage',$stage)->first();
    return $getDate ? $getDate->dated_at : '';
}

}