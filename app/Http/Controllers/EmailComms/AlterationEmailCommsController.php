<?php

namespace App\Http\Controllers\EmailComms;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alteration;
use App\Models\Licence;

class AlterationEmailCommsController extends Controller
{

    public function getAlterationTemplate(Request $request){

        $alterations = Alteration::with("licence")->when($request, function($query) use($request){
            $query->when(request('month'), function($query) {                    
                $query->whereHas('licence', function($query) {
                    $query->whereMonth('licence_date', request('month'))
                    ->whereNull('deleted_at');
                });
            })
            ->when(request('province'), function ($query) use ($request) {
                $query->whereHas('licence', function($query) use($request){
                    $query->where('province',$request->province);
                });
               
            })
          ->when(!empty(request('stage')), function ($query) use ($request) {
            $query->where('status',$request->stage);
        });
        })
        ->where(function($query){
            $query->where('status','1')
                    ->orWhere('status','200')
                    ->orWhere('status','400')
                    ->orWhere('status','500')
                    ->orWhere('status','600')
                    ->orWhere('status','700');   
                     })
    ->whereNull('deleted_at')
        ->orderBy('status','asc')
           ->paginate(20)->withQueryString();


    return Inertia::render('EmailComms/Alteration',['alterations' => $alterations]);
}

}
