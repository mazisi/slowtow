<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use App\Mail\TemporalLicenceMailer;
use Illuminate\Support\Facades\Mail;
use App\Models\TemporalLicenceDocument;


class HandleTemporalLicenceMail {

    //Status keys:
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Temporary Licence Documents 
// 5 => Payment To The Liquor Board 
// 6 => Scanned Application
// 7 => Temporary Licence Lodged 
// 8 => Temporary Licence Issued 
// 9 => Temporary Licence Delivered

  public function dispatchTemporalMail(Request $request){
    try {
        $temporal_licence = TemporalLicence::whereSlug($request->temp_licence_slug)->firstOrFail();
        $stage = '';
        $temp_licence_stage = '';  

        return back()->with('error','Waiting for templates.');
        
        switch ($temporal_licence->status) {            
            case '1':
                $stage = '1';
                $temp_licence_stage = 'Client Quoted';                
                break;
            case '2':
                $stage = '2';
                $temp_licence_stage = 'Client Invoiced';
                break;
            case '3':
                $stage = '3';
                $temp_licence_stage = 'Client Paid';
                break;
            case '5':
                $stage = '5';
                $temp_licence_stage = 'Renewal Issued';
                break;
            default:
            return back()->with('error','Could not send email.');
                break;
        }
        $get_doc = TemporalLicenceDocument::where('alteration_id',$temporal_licence->id)->where('doc_type',$temp_licence_stage)->first();
        //check if licence already inserted in emails 
        //$get_email_status = Email::where('stage', $stage)->where('model_type','alterations')->where('model_id',$temporal_licence->id)->first();

        if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
            //         //$this->insertUnsentEmails($temporal_licence, $error_message);               
                    return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
                }        
        
        $this->handleRenewalEmail($temporal_licence);
       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($temporal_licence, $error_message, $temp_licence_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handleRenewalEmail($temporal_licence) {
    $email = $temporal_licence->licence->company->email; //primary email
    $email1 = $temporal_licence->licence->company->email1;
    $email2 = $temporal_licence->licence->company->email2;;

    //  Mail::to('mazisimsebele18@gmail.com')
    // ->cc(['mazisi@mrnlabs.com', 'info@slotow.co.za',
    // 'sales@slotow.co.za'])->send(new AlterationMailer($temporal_licence, $request->mail_body));
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }


    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za'])->send(new TemporalLicenceMailer($temporal_licence, $request->mail_body)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc('sales@slotow.co.za')
        ->send(new TemporalLicenceMailer($temporal_licence, $request->mail_body));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc('sales@slotow.co.za')
            ->send(new TemporalLicenceMailer($temporal_licence, $request->mail_body));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
  }

//   function insertUnsentEmails($temporal_licence, $error_message, $temp_licence_stage='') : void {
//     Email::insert([
//         'model_type' => 'alterations',
//         'model_id' => $temporal_licence->id,
//         'trading_name' => $temporal_licence->licence->trading_name,
//         'model_slug' => $temporal_licence->slug,
//         'parent_licence_slug' => $temporal_licence->licence->slug,
//         'status' => 'Email NOT Sent',
//         'stage' => $temp_licence_stage,
//         'feedback' => $error_message,
//         'created_at' => now(),
//         'updated_at' => now()
//     ]);
// }
}