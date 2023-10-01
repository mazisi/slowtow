<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Models\Email;
use App\Mail\RenewalMailer;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\Mail;


class HandleRenewalMail {

  public function dispatchRenewalMail(Request $request){
    try {
        $renewal = LicenceRenewal::with('licence.company')->whereSlug($request->renewal_slug)->firstOrFail();
        $stage = '';
        $renewal_stage = '';  

        
        switch ($renewal->status) {            
            case '1':
                $stage = '1';
                $renewal_stage = 'Client Quoted';                
                break;
            case '2':
                $stage = '2';
                $renewal_stage = 'Client Invoiced';
                break;
            case '3':
                $stage = '3';
                $renewal_stage = 'Client Paid';
                break;
            case '5':
                $stage = '5';
                $renewal_stage = 'Renewal Issued';
                break;
            default:
            return back()->with('error','Could not send email.');
                break;
        }
        $get_doc = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type',$renewal_stage)->first();
        //check if licence already inserted in emails 
        //$get_email_status = Email::where('stage', $stage)->where('model_type','renewals')->where('model_id',$renewal->id)->first();

        //  $error_message = '';
        // if(is_null($get_email_status)){
        //     if(is_null($get_doc)){
        //         $error_message = 'Quote Document Not Uploaded';                
        //         //$this->insertUnsentEmails($renewal, $error_message);               
        //         return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
        //     }
        // }         
        
        //if mail sent then update is quote sent for reporting purposes
        $renewal->update(['is_quote_sent' => 'true']);

       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($renewal, $error_message, $renewal_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handleRenewalEmail() {
    $email = $renewal->licence->company->email; //primary email
    $email1 = $renewal->licence->company->email1;
    $email2 = $renewal->licence->company->email2;;

    //  Mail::to('mazisimsebele18@gmail.com')
    // ->cc(['mazisi@mrnlabs.com', 'info@slotow.co.za',
    // 'sales@slotow.co.za'])->send(new RenewalMailer($renewal, $request->mail_body));
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }


    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za'])->send(new RenewalMailer($renewal, $request->mail_body)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc('sales@slotow.co.za')
        ->send(new RenewalMailer($renewal, $request->mail_body));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc('sales@slotow.co.za')
            ->send(new RenewalMailer($renewal, $request->mail_body));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
  }

//   function insertUnsentEmails($renewal, $error_message, $renewal_stage='') : void {
//     Email::insert([
//         'model_type' => 'renewals',
//         'model_id' => $renewal->id,
//         'trading_name' => $renewal->licence->trading_name,
//         'model_slug' => $renewal->slug,
//         'parent_licence_slug' => $renewal->licence->slug,
//         'status' => 'Email NOT Sent',
//         'stage' => $renewal_stage,
//         'feedback' => $error_message,
//         'created_at' => now(),
//         'updated_at' => now()
//     ]);
// }
}