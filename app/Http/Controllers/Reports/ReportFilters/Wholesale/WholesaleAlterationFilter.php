<?php

namespace App\Http\Controllers\Reports\ReportFilters\Wholesale;

use Illuminate\Support\Facades\DB;

class WholesaleAlterationFilter {


 function filter($request){
    
  return DB::table('alterations')
        ->selectRaw("alterations.id,report_type, licences.trading_name, licences.belongs_to, licences.licence_number, licences.province, 
       licences.board_region,licence_type_id,alterations.date, licences.company_id,licences.people_id,
        alterations.status, licence_date, is_licence_active")
        ->join('licences', 'licences.id' , '=', 'alterations.licence_id' )
    

      ->when($request,function($query){
          $query->when(request('month_from') && request('month_to'), function($query){
            $query->whereBetween(DB::raw('MONTH(alterations.logded_at)'),[request('month_from'), request('month_to')]);
           })

          ->when(request('month_from') && !request('month_to'), function ($query)  {
            $query->whereMonth('alterations.logded_at', request('month_from'));
          })
          ->when(request('activeStatus') == 'Active', function ($query) {
              $query->where('is_licence_active',1);
          })
          ->when(request('activeStatus') == 'Inactive', function ($query) {
              $query->where('is_licence_active',false);
          })
          ->when(request('province'), function ($query) {
              $query->whereIn('province',array_values(explode(",",request('province'))));
          })
          
          ->when(request('boardRegion') && request('report_type') == 'retail', function ($query) {
              $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
          })
          
           ->when(request('alteration_stages'), function ($query) {
                  $query->whereIn('alterations.status',array_values(explode(",",request('alteration_stages'))));
              })
          ->when(request('applicant'), function ($query) {
              $query->where('belongs_to',request('applicant'));
          })
          
          ->when(request('year') && request('year') !== 'null', function ($query) {
               $query->whereYear('logded_at', request('year'));
           })
          ->when(request('licence_types'), function ($query) {
              $query->whereIn('licence_type_id',getLicenceTypeByProvince());
           })

              ->when(request('is_licence_complete') === 'Pending' && request('report_type') == 'wholesale' , function ($query){
                $query->where(function ($query) {
                    $query->where('alterations.status','<', 800);
                });
            
            })

          ->when(request('is_licence_complete') === 'Complete' && request('report_type') == 'wholesale' , function ($query)  {
            $query->where('alterations.status', '>=', 800);
          });

      })
      ->whereNull('alterations.deleted_at')
      ->orderBy('trading_name')
      ->where('report_type', 'wholesale')
       ->get();
 }
}