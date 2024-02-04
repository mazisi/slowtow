<?php

namespace App\Http\Controllers\EmailComms;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TemporalLicence;

class TemporaryLicenceEmailCommsController extends Controller{

  function getTemporaryLicenceTemplate(Request $request) {

    $temp_licences = TemporalLicence::with('company','people')
    ->when(request(), 
        function ($query) use($request) {           
            // $query->whereHas('company', function($query){
            //     $query->where('name', 'like', '%'.request('term').'%');

            // })->orWhereHas('people', function($query){
            //     $query->where('full_name', 'like', '%'.request('term').'%');                                      
            // })

            $query->when(request('month'), function($query) {                    
                $query->whereMonth('start_date', request('month'));
              })
        
          ->when(request('province'), function ($query) use ($request) {
              $query->where('address',$request->province);
          })

          ->when(request('stage'), function ($query) use ($request) {
            $query->where('status',$request->stage);
        });
        
        })->where(function($query){
        $query->where('status','100')
              ->orWhere('status','200')
              ->orWhere('status','500')
              ->orWhere('status','700')
              ->orWhere('status','800');
        })->latest()->paginate(20)->withQueryString();


return Inertia::render('EmailComms/TempLicences',['temp_licences' => $temp_licences]);
    
  }

}