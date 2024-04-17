<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Mail\RenewalMailer;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\Mail;


class HandleRenewalMail {

  public function dispatchRenewalMail(Request $request){
      
      
    try {
        $renewal = LicenceRenewal::with('licence.company')->whereSlug($request->renewal_slug)->firstOrFail();
        
        if($renewal->licence->belongs_to == 'Company'){

            if(is_null($renewal->licence->company->email)){
              return back()->with('error','Mail not sent. This company does not have primary email.');
             }
 
         }else{
            if(is_null($renewal->licence->people->email_address_1)){
                return back()->with('error','Mail not sent. This individual does not have primary email.');
            }
         }

        $renewal_stage = '';  

        if($renewal->licence->type == 'retail'){
                switch ($renewal->status) {            
                    case '100':
                        $renewal_stage = 'Client Quoted';                
                        break;
                    case '200':
                        $renewal_stage = 'Client Invoiced';
                        break;
                    case '300':
                        $renewal_stage = 'Client Paid';
                        break;
                    case '500':
                        $renewal_stage = 'Renewal Issued';
                        break;
                    default:
                    return back()->with('error','Could not send email.');
                        break;
                }
    }else{
        $this->getWholesaleStages($renewal);
    }

        $get_doc = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type',$renewal_stage)->first();
       
        if(is_null($get_doc)){              
            return back()->with('error','Mail NOT SENT!.Document is not uploaded.');
        }   
        
        
        $this->handle($renewal, $get_doc->document);
        
        //if mail sent then update is quote sent for reporting purposes
        $renewal->update(['is_quote_sent' => 'true']);

       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($renewal, $error_message, $renewal_stage);  
      return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handle($renewal,$document) {

    $full_document_path = env('BLOB_FILE_PATH').$document;

    if($renewal->licence->belongs_to == 'Company'){
        $this->emailCompany($renewal, $full_document_path);
    }else{
        $this->emailPerson($renewal, $full_document_path);
    }
    
  }

  function getWholesaleStages($renewal){
    $renewal_stage = ''; 
    switch ($renewal->status) {    
        case '100':
            $renewal_stage = 'Renewal Notice Received';                
            break;
        case '200':
            $renewal_stage = 'Turnover Information Requested';
            break;
        case '300':
            $renewal_stage = 'Turnover Information Received';
            break;
        case '400':
            $renewal_stage = 'Annual Return Submited';
            break;
        case '500':
            $renewal_stage = 'Client Invoiced';
            break;
        case '600':
            $renewal_stage = 'Client Paid';
            break;
        case '700':
            $renewal_stage = 'Payment to the National Liquor Authority';
            break;
        case '800':
            $renewal_stage = 'Renewal Forms Sent';
            break;
        case '900':
            $renewal_stage = 'Renewal Forms Received';
            break;

        case '1000':
            $renewal_stage = 'Renewal Forms Preparation';
            break;
        case '1100':
            $renewal_stage = 'Renewal Submitted';
            break;
        case '1200':
            $renewal_stage = 'Additional Documents/Information Requested';
            break;
        case '1300':
            $renewal_stage = 'Renewal Pending QA';
            break;
        case '1400':
            $renewal_stage = 'Renewal Awaiting Sign Off';
            break;
        case '1500':
            $renewal_stage = 'Renewal Approved';
            break;
        default:
        return back()->with('error','Could not send email.');
            break;
    }
    return $renewal_stage;
  }

  function emailPerson($renewal, $full_document_path) {
    $email = $renewal->licence->people->email_address_1; //primary email
    $email1 = $renewal->licence->people->email_address_2;
   
    
    if(! is_null($email) && $email1){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new RenewalMailer($renewal, request('mail_body'), $full_document_path)); 
    }

    elseif($email && !$email1){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new RenewalMailer($renewal, request('mail_body'), $full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }

    
}

  function emailCompany($renewal, $full_document_path){

    $email = $renewal->licence->company->email; //primary email
    $email1 = $renewal->licence->company->email1;
    $email2 = $renewal->licence->company->email2;
  

    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za','info@goverify.co.za'])
        ->send(new RenewalMailer($renewal, request('mail_body'),$full_document_path));
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new RenewalMailer($renewal, request('mail_body'),$full_document_path));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new RenewalMailer($renewal, request('mail_body'),$full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
    
  }
}