<?php

namespace App\Http\Controllers;

use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Mail\NominationMailer;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\Mail;

class NominationEmailCommsController extends Controller
{
    public function dispatchMail(Request $request){
        try {
           
  // 2 => Client Invoiced
  // 3 => Client Paid
  // 4 => Payment to the Liquor Board
  // 5 => Select nominees
  // 6 => Documents Required 
  // 7 => Nomination Lodged 
  // 8 => Nomination issued
  // 9 => Nomination Delievered  
  
            $licence = Nomination::with('licence.company')->whereSlug($request->nomination_slug)->firstOrFail();
        switch ($licence->status) {            
                case '1':                
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Client Quoted')->first();
                    break;
                case '2':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Client Invoiced')->first();
                    break;
                case '3':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Client Paid')->first();
                    break;
                case '4':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Payment To The Liquor Board')->first();
                    break;
                case '7':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Nomination Logded')->first();
                    break;
                case '8':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Nomination Issued')->first();
                    break;
                default:
                return back()->with('error','Could not locate document.');
                    break;
            }
            if(is_null($get_doc)){
                return back()->with('error','Mail NOT SENT!!!!.Document IS NOT YET UPLOADED.');
            }
            $recipients = [$licence->licence->company->email,$licence->licence->company->email1,$licence->licence->company->email2];
            foreach ($recipients as $recipient) {
                if(is_null($recipient)){
                    continue;
                }
                Mail::to($recipient)->send(new NominationMailer($licence, $request->mail_body));    
            }
            return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {dd($th);
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }
}
