<?php

namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Support\Facades\DB;

class ExistingLicenceReportFilter{


  function filter($request){
     return DB::table('licences')
      ->selectRaw("licences.id, is_licence_active, belongs_to,company_id,people_id,trading_name,licence_type_id, licence_types.licence_type,licences.province, licence_number,
                   board_region,licence_date, licences.status, is_new_app")

      ->join('licence_types', 'licences.licence_type_id' , '=', 'licence_types.id')

         ->when($request,function($query){
            $query->when(request('month_from') && request('month_to'), function($query){
                $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
             })

             ->when(request('month_from') && !request('month_to'), function ($query){
                $query->whereMonth('licence_date', request('month_from'));
            })

            ->when(request('activeStatus') === 'Active', function ($query) {
                $query->where('is_licence_active',1);
            })
            ->when(request('activeStatus') === 'Inactive', function ($query) {
                $query->where('is_licence_active',0);
            })

            ->when(request('province'), function ($query) {
                $query->whereIn('licences.province',array_values(explode(",",request('province'))));
            })
            ->when(request('boardRegion') && request('report_type') !== 'retail', function ($query) {
                $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
            })
            ->when(request('new_app_stages'), function ($query) {
                $query->whereIn('status', array_values(explode(",",request('new_app_stages'))));
            })
            ->when(request('applicant'), function ($query) {
                $query->where('belongs_to',request('applicant'));
            })
            ->when(request('licence_types'), function ($query) {
                $query->whereIn('licence_type_id',getLicenceTypeByProvince());
            })
            
            ->when(request('year') && request('year') !== 'null', function ($query) {
                   $query->whereYear('licence_date', request('year'));
             })

           // ->when(request('selectedDates'), function ($query) {
                //$query->where(DB::raw('YEAR(licence_date)'),$request->selectedDates);
             //})

             ->when(request('is_licence_complete') === 'Pending', function ($query)  {
                $query->where('status','<', 2300)
                ->orWhereNull('status');
            })

            ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                $query->where('status','>=', 2300);
            });
            
            })
            
            ->whereNull('deleted_at')
            ->where(function($query){
                $query->whereNull('is_new_app')
                ->orWhere('is_new_app',0);
            })
            ->where('type','retail')
            ->orderBy('trading_name')
            ->get();
  }
}