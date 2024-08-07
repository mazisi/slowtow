<?php

namespace App\Http\Controllers\EmailComms;

use Inertia\Inertia;
use App\Models\Email;
use App\Mail\TransferMailer;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class TransferEmailCommsController extends Controller
{

    public function getLicenceTransfers(request $request){

        $transfers = LicenceTransfer::with("licence")->when($request, function($query) use($request){
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
        ->orWhereHas('licence', function ($query) {
            $query->where('type', 'wholesale');
        })
        ->orWhere(function($query){
            $query->whereIn('status',[100,200,500,600,700,800]);
        })->whereNull('deleted_at')
        ->orderBy('status','asc')
           ->paginate(20)->withQueryString();


    return Inertia::render('EmailComms/Transfer',['transfers' => $transfers]);
}


}
