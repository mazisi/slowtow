<?php
namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Support\Facades\DB;

class TransferReportFilter {


  function filter($request){
    return DB::table('licence_transfers')
    ->selectRaw("licence_transfers.id, is_licence_active,licences.belongs_to, licences.company_id,licences.people_id,trading_name, licence_transfers.date, 
                 licence_transfers.status,
                 board_region,transfered_to,transfered_from,province, licence_number")

    ->join('licences', 'licences.id' , '=', 'licence_transfers.licence_id')

    ->when(function($query){
        $query->when(request('month_from') && request('month_to'), function($query){
            $query->whereBetween(DB::raw('MONTH(date)'),[request('month_from'), request('month_to')]);
        })

        ->when(request('month_from') && !request('month_to'), function ($query)  {
            $query->whereMonth('date', request('month_from'));
        })
        ->when(request('province'), function ($query)  {
            $query->whereIn('licences.province',array_values(explode(",",request('province'))));
        })
        ->when(request('boardRegion') && request('report_type') == 'retail', function ($query)  {
            $query->whereIn('licences.board_region',array_values(explode(",",request('boardRegion'))));
        })
        
        ->when(request('applicant'), function ($query)  {
            $query->where('belongs_to',request('applicant'));
        })

        ->when(request('activeStatus') === 'Inactive', function ($query) {
            $query->where('is_licence_active',false);
        })

        ->when(request('activeStatus') == 'Active', function ($query) {
            $query->where('is_licence_active', 1);
        })

        ->when(request('licence_types'), function ($query)  {
            $query->where('licence_type_id',getLicenceTypeByProvince());
        })

        ->when(request('is_licence_complete') === 'Pending', function ($query)  {
            $query->where('licence_transfers.status','<', 900);
        })

        ->when(request('is_licence_complete') === 'Complete', function ($query)  {
            $query->where('licence_transfers.status','>=',900);
        });

          })->when(request('year'), function ($query) {
                $query->whereYear('date',request('year'));
          })
          ->when(request('transfer_stages'), function ($query) {
              $query->whereIn('licence_transfers.status', array_values(explode(",",request('transfer_stages'))));
          })
          ->whereNull('licences.deleted_at')->whereNull('licence_transfers.deleted_at')
          ->where('report_type','retail')
          ->orderBy('trading_name')->get();
  }
}