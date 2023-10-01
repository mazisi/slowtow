<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Models\Email;
use App\Models\Alteration;
use Illuminate\Http\Request;
use App\Mail\AlterationMailer;
use App\Models\AlterationDocument;
use Illuminate\Support\Facades\Mail;


class HandleAlterationMail {

    //Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered

  public function dispatchAlterationMail(Request $request){
    try {
        $alteration = Alteration::with('licence.company')->whereSlug($request->alteration_slug)->firstOrFail();
        $stage = '';
        $alteration_stage = '';  

        return back()->with('error','Waiting for templates.');
        
        switch ($alteration->status) {            
            case '1':
                $stage = '1';
                $alteration_stage = 'Client Quoted';                
                break;
            case '2':
                $stage = '2';
                $alteration_stage = 'Client Invoiced';
                break;
            case '3':
                $stage = '3';
                $alteration_stage = 'Client Paid';
                break;
            case '5':
                $stage = '5';
                $alteration_stage = 'Renewal Issued';
                break;
            default:
            return back()->with('error','Could not send email.');
                break;
        }
        $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type',$alteration_stage)->first();
        //check if licence already inserted in emails 
        //$get_email_status = Email::where('stage', $stage)->where('model_type','alterations')->where('model_id',$alteration->id)->first();

        //  $error_message = '';
        if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
            //         //$this->insertUnsentEmails($alteration, $error_message);               
                    return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
         }        
        
         $this->handleRenewalEmail($alteration);
       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($alteration, $error_message, $alteration_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handleRenewalEmail($alteration) {
    $email = $alteration->licence->company->email; //primary email
    $email1 = $alteration->licence->company->email1;
    $email2 = $alteration->licence->company->email2;;

    //  Mail::to('mazisimsebele18@gmail.com')
    // ->cc(['mazisi@mrnlabs.com', 'info@slotow.co.za',
    // 'sales@slotow.co.za'])->send(new AlterationMailer($alteration, $request->mail_body));
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }


    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za'])->send(new AlterationMailer($alteration, $request->mail_body)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc('sales@slotow.co.za')
        ->send(new AlterationMailer($alteration, $request->mail_body));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc('sales@slotow.co.za')
            ->send(new AlterationMailer($alteration, $request->mail_body));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
  }


}