<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;

class ReportController extends Controller
{
    public function index(){
        $renewals = LicenceRenewal::with(['licence','renewal_documents' => function ($query){
            $query ->where('doc_type','Client Quoted');            
          }])->paginate(1);

          //Emails that aren t sent
          $emails = Email::with(['licence_renewals.licence' => function ($query){
            // $query ->where('doc_type','Client Quoted');            
          }])->where('model_type','Renewal')->get();
          
        return Inertia::render('Report',[
            'renewals' => $renewals,
            'emails' => $emails]);
    }

}
