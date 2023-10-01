<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use Illuminate\Http\Request;
use App\Mail\TemporalLicenceMailer;
use App\Models\Licence;
use App\Models\LicenceDocument;
use Illuminate\Support\Facades\Mail;
use App\Models\TemporalLicenceDocument;


class HandleNewAppMail {

//The following are status keys
  // 1. Client Quoted
  // 2. Deposit Invoice
  // 3. Deposit Paid
  // 4. Payment to the Liquor Board
  // 5. Prepare New Application
  // 6. Scanned Application
  // 7. Application Lodged
  // 8. Initial Inspection
  // 9. Liquor Board Requests
  // 10. Final Inspection
  // 11. Activation Fee Requested
  // 12. Client Finalisation Invoice
  // 13. Client Paid
  // 14. Activation Fee Paid
  // 15. Licence Issued
  // 16. Licence Delivered 

  public function dispatchNewAppMail(Request $request){
    try {
        $licence = Licence::whereSlug($request->licence_slug)->firstOrFail();
        $stage = '';
        $licence_stage = '';  

        return back()->with('error','Waiting for templates.');
        
        switch ($licence->status) {            
            case '1':
                $stage = '1';
                $licence_stage = 'Client Quoted';                
                break;
            case '2':
                $stage = '2';
                $licence_stage = 'Client Invoiced';
                break;
            case '3':
                $stage = '3';
                $licence_stage = 'Client Paid';
                break;
            case '5':
                $stage = '5';
                $licence_stage = 'Renewal Issued';
                break;
            default:
            return back()->with('error','Could not send email.');
                break;
        }
        $get_doc = LicenceDocument::where('licence_id',$licence->id)->where('doc_type',$licence_stage)->first();
        //check if licence already inserted in emails 
        //$get_email_status = Email::where('stage', $stage)->where('model_type','alterations')->where('model_id',$licence->id)->first();

        //  $error_message = '';
        // if(is_null($get_email_status)){
        //     if(is_null($get_doc)){
        //         $error_message = 'Quote Document Not Uploaded';                
        //         //$this->insertUnsentEmails($licence, $error_message);               
        //         return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
        //     }
        // }         
        

       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($licence, $error_message, $licence_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handleRenewalEmail() {
    $email = $licence->company->email; //primary email
    $email1 = $licence->company->email1;
    $email2 = $licence->company->email2;;

    //  Mail::to('mazisimsebele18@gmail.com')
    // ->cc(['mazisi@mrnlabs.com', 'info@slotow.co.za',
    // 'sales@slotow.co.za'])->send(new AlterationMailer($licence, $request->mail_body));
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }


    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za'])->send(new TemporalLicenceMailer($licence, $request->mail_body)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc('sales@slotow.co.za')
        ->send(new TemporalLicenceMailer($licence, $request->mail_body));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc('sales@slotow.co.za')
            ->send(new TemporalLicenceMailer($licence, $request->mail_body));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
  }

//   function insertUnsentEmails($licence, $error_message, $licence_stage='') : void {
//     Email::insert([
//         'model_type' => 'alterations',
//         'model_id' => $licence->id,
//         'trading_name' => $licence->licence->trading_name,
//         'model_slug' => $licence->slug,
//         'parent_licence_slug' => $licence->licence->slug,
//         'status' => 'Email NOT Sent',
//         'stage' => $licence_stage,
//         'feedback' => $error_message,
//         'created_at' => now(),
//         'updated_at' => now()
//     ]);
// }
}