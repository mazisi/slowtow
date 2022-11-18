<?php

namespace App\Http\Controllers;

use App\Models\Email;
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
                    $stage='Client Quoted';
                    break;
                case '2':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Client Invoiced')->first();
                    $stage='Client Invoiced';
                    break;
                case '3':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Client Paid')->first();
                    $stage='Client Paid';
                    break;
                case '4':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Payment To The Liquor Board')->first();
                    $stage='Payment To The Liquor Board';
                    break;
                case '7':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Nomination Logded')->first();
                    $stage='Nomination Logded';
                    break;
                case '8':
                    $get_doc = NominationDocument::where('nomination_id',$licence->id)->where('doc_type','Nomination Issued')->first();
                    $stage='Nomination Issued';
                    break;
                default:
                return back()->with('error','An error occurred.');
                    break;
            }
            //check if licence already inserted in emails 
            $get_email_status = Email::where('stage', $stage)->where('model_type','nominations')->where('model_id',$licence->id)->first();

             $error_message = '';
            if(is_null($get_email_status)){
                if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
                    Email::insert([
                        'model_type' => 'nominations',
                        'model_id' => $licence->id,
                        'model_slug' => $licence->slug,
                        'parent_licence_slug' => $licence->licence->slug,
                        'trading_name' => $licence->licence->trading_name,
                        'status' => 'Email NOT Sent',
                        'stage' => $stage,
                        'feedback' => $error_message,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);                   
                    return back()->with('error','Mail NOT SENT!!!!. Document is not yet uploaded.');
                }
            }
            
            $email = $licence->licence->company->email;
            $email1= $licence->licence->company->email1;
            $email2 = $licence->licence->company->email2;

                if(!is_null($email)){
                    Mail::to($email)->send(new NominationMailer($licence, $request->mail_body));   
                }elseif(is_null($email) && !is_null($email1)){
                    Mail::to($email1)->send(new NominationMailer($licence, $request->mail_body));
                }elseif(is_null($email) && is_null($email1) && !is_null($email2)){
                    Mail::to($email2)->send(new NominationMailer($licence, $request->mail_body));
                }else{
                    return back()->with('success','Mail NOT sent. Company does not have email addresses.');
                }
             
            return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {
            $error_message = 'Server Error.';
            Email::insert([
                'model_type' => 'nominations',
                'model_id' => $licence->id,
                'trading_name' => $licence->licence->trading_name,
                'model_slug' => $licence->slug,
                'parent_licence_slug' => $licence->licence->slug,
                'status' => 'Email NOT Sent',
                'stage' => $renewal_stage,
                'feedback' => $error_message,
                'created_at' => now(),
                'updated_at' => now()
            ]); 
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }
}
