<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Actions\RenewalEmailTemplate;
use App\Actions\TransferMailTemplate;
use App\Models\Nomination;
use App\Actions\NominationMailTemplate;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;

class EmailCommsController extends Controller
{
   public function index(Request $request){

            $renewals = (new RenewalEmailTemplate)->getView($request);

        return Inertia::render('EmailComms/EmailComm',['renewals' => $renewals]);
    }


  
    public function getMailTemplate($slug,$comm_variation){         
        switch ($comm_variation) {            
            case 'renewals': 
                $licence = LicenceRenewal::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new RenewalEmailTemplate)->getMailTemplate($licence);            
                return Inertia::render('EmailComms/MailTemplate',['licence' => $licence,'template' => $template]);
                break;

            case 'alterations':
                $this->alterationMailer($slug);
                break;

            case 'transfers':
                $licence = LicenceTransfer::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new TransferMailTemplate)->getMailTemplate($licence);     
                return Inertia::render('EmailComms/TransferTemplate',['licence' => $licence,'template' => $template]);
                break;
                
            case 'nominations': 
                $nomination = Nomination::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new NominationMailTemplate)->getMailTemplate($nomination);             
                return Inertia::render('EmailComms/NominationTemplate',['licence' => $nomination,'template' => $template]);
                break;
            case 'new-app':
                $this->newAppMailer($slug);
                break;
    
            default:
            return back()->with('error','An error occured.Please try again.');
                break;
        }
    }


}
