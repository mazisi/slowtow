<?php

namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Support\Facades\DB;

class RenewalReportFilter {

  function filter($request){
   return DB::table('licence_renewals')
   ->selectRaw("licence_renewals.id,report_type, is_licence_active, trading_name,licences.company_id, licences.people_id, licences.belongs_to, board_region,licence_number, licences.licence_date,licence_renewals.status,
                is_quote_sent, licence_renewals.date")

   ->join('licences', 'licences.id' , '=', 'licence_renewals.licence_id')

       ->when($request,function($query){
           $query->when(request('month_from') && request('month_to'), function($query){
               $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
           })
 
           ->when(request('month_from') && !request('month_to'), function ($query)  {
               $query->whereMonth('licence_date', request('month_from'));
           })

           ->when(request('province'), function ($query)  {
               $query->whereIn('licences.province',array_values(explode(",",request('province'))));
           })

           ->when(request('activeStatus') === 'Inactive', function ($query) {
               $query->where('is_licence_active',false);
           })

           ->when(request('activeStatus') == 'Active', function ($query) {
               $query->where('is_licence_active', 1);
           })

           ->when(request('boardRegion'), function ($query)  {
               $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
           })
           ->when(request('applicant'), function ($query)  {
               $query->where('belongs_to',request('applicant'));
           })
           ->when(request('year'), function ($query) {
              $query->where('date',request('year'));
           })

           ->when(request('renewal_stages'), function ($query) {
               $query->whereIn('licence_renewals.status',array_values(explode(",",request('renewal_stages'))));
           })
           ->when(request('licence_types'), function ($query)  {
               $query->whereIn('licence_type_id',getLicenceTypeByProvince());
           })
           ->when(request('is_licence_complete') === 'Pending', function ($query)  {
                $query->where('licence_renewals.status','<', 500)
                ->orWhereNull('licence_renewals.status');
            })
 
            ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                $query->where('licence_renewals.status','>=', 500);
            });

           })->whereNull('licences.deleted_at')->whereNull('licence_renewals.deleted_at')
           ->where('report_type','retail')
           ->orderBy('trading_name')
           ->get();
  }
}