<?php

namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Support\Facades\DB;

class NominationReportFilter {

  function filter($request){

    return DB::table('nominations')

    ->selectRaw("nominations.id, licences.trading_name, licences.belongs_to,licences.company_id,licences.people_id, people.full_name, licences.licence_number, licences.province, 
                 nominations.payment_to_liquor_board_at, nominations.nomination_lodged_at, 
                  nomination_lodged_at,nomination_lodged_at, '' as date_granted , 
                  nominations.status, nominations.nomination_issued_at,board_region, nominations.year, is_licence_active")
              ->join('nomination_people', 'nomination_people.nomination_id' , '=', 'nominations.id' )
              ->join('people', 'people.id' , '=', 'nomination_people.people_id' )
              ->join('licences', 'licences.id' , '=', 'nominations.licence_id' )

        ->when($request,function($query){
            $query->when(request('month_from') && request('month_to'), function($query){
                $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
            })

            ->when(request('month_from') && !request('month_to'), function ($query)  {
                $query->whereMonth('licence_date', request('month_from'));
            })
            ->when(request('activeStatus') === 'Active', function ($query) {
              $query->where('is_licence_active',1);
          })
          ->when(request('activeStatus') === 'Inactive', function ($query) {
              $query->where('is_licence_active',false);
           })
            ->when(request('province'), function ($query) {
                $query->whereIn('licences.province',array_values(explode(",",request('province'))));
            })
            ->when(request('licence_types'), function ($query)  {
                $query->whereIn('licence_type_id',getLicenceTypeByProvince());
            })

            ->when(request('boardRegion'), function ($query) {
                $query->whereIn('board_region', array_values(explode(",",request('boardRegion'))));
            })
            ->when(request('applicant'), function ($query) {
                $query->where('belongs_to',request('applicant'));
            });

              })
              ->when(request('nomination_stages'), function ($query) {
                  $query->whereIn('nominations.status',array_values(explode(",",request('nomination_stages'))));
            })

            ->when(request('is_licence_complete') === 'Pending', function ($query)  {
              $query->where('nominations.status','<', 900)
                    ->orWhereNull('nominations.status');
          })

          ->when(request('is_licence_complete') === 'Complete', function ($query)  {
              $query->where('nominations.status','>=', 900);
          })
          
          ->when(request('year'), function ($query) {
                    $query->where('nominations.year', request('year'));
                  })
            ->whereNull('licences.deleted_at')->whereNull('nominations.deleted_at')
            
      ->orderBy('trading_name')
        ->get();
  }
}