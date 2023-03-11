<?php

namespace App\Actions;

use Illuminate\Http\Request;

class LicenceFilterAction{
  
  public static function searchOnly($query){

   if(request('term') && !request('licence_date') && !request('licence_type') && !request('active_status') && !request('province')){

    
               $query->where('trading_name','LIKE','%'.request('term').'%')
                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                ->orWhere('licence_number','LIKE','%'.request('term').'%');
    
   }
    
  }

}