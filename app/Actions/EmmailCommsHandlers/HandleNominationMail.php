<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Models\Email;
use App\Mail\NominationMailer;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\Mail;


class HandleNominationMail {

    public function dispatchNominationMail(Request $request){
        try {
           
  // 1= > Client Quoted
  // 2 => Client Invoiced
  // 3 => Client Paid
  // 4 => Payment to the Liquor Board
  // 5 => Select nominees
  // 6 => Prepare Nomination Application 
  // 7  => Scanned Application
  // 8 => Nomination Lodged 
  // 9 => Nomination Issued
  // 10 => Nomination Delivered 
  
            $nomination = Nomination::with('licence.company')->whereSlug($request->nomination_slug)->firstOrFail();
        switch ($nomination->status) {            
                case '1':                
                    $stage='Client Quoted';
                    $get_doc = $this->getDocumentType($nomination->id, $stage);
                    break;
                case '2':
                    $stage='Client Invoiced';
                    $get_doc = $this->getDocumentType($nomination->id, $stage);
                    break;
                case '3':
                    $stage='Client Paid';
                    $get_doc = $this->getDocumentType($nomination->id, $stage);
                    break;
                case '4':
                    $stage='Payment To The Liquor Board';
                    $get_doc = $this->getDocumentType($nomination->id, $stage);
                    break;
                case '8':
                    $stage='Nomination Logded';
                    $get_doc = $this->getDocumentType($nomination->id, $stage);
                    break;
                case '9':
                    $stage='Nomination Issued';
                    $get_doc = $this->getDocumentType($nomination->id, $stage);
                    break;
                default:
                return back()->with('error','An error occurred.');
                    break;
            }
            
            if(is_null($get_doc)){               
                return back()->with('error',' Document is not yet uploaded.');
            }

            
            $email = $nomination->licence->company->email;
            $email1= $nomination->licence->company->email1;
            $email2 = $nomination->licence->company->email2;


            //If there is no primary email
                if(! $email){
                    return back()->with('error','Mail NOT sent. Primary email not found.');
                }
            //  Mail::to('mazisimsebele18@gmail.com')
            //     ->cc(['mazisi@mrnlabs.com', 'test@gmail.com'])->send(new NominationMailer($nomination, $request->mail_body));


            $full_document_path = env('BLOB_FILE_PATH').$get_doc->document;

            if(! is_null($email) && $email1 && $email2){
                Mail::to($email)
                ->cc([$email1,'info@slotow.co.za'])
                ->bcc([$email2,'sales@slotow.co.za','info@goverify.co.za'])->send(new NominationMailer($nomination, $request->mail_body,$full_document_path)); 
            }
        
            elseif($email && $email1 && !$email2){
                Mail::to($email)
                ->cc([$email1, 'info@slotow.co.za'])
                ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
                ->send(new NominationMailer($nomination, $request->mail_body,$full_document_path));
            }
            
            elseif($email && !$email1 && !$email2){
                    Mail::to($email)
                    ->cc('info@slotow.co.za')
                    ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
                    ->send(new NominationMailer($nomination, $request->mail_body,$full_document_path));
            }else{
                return back()->with('error','Mail NOT sent. Company does not have email addresses.');
            }
             
            return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {
            $error_message = 'Server Error.';
            //$this->insertUnsentEmails($nomination, $error_message, $stage);
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }

 
  function getDocumentType($nomination_id, $doc_type){
    $nomination_document = NominationDocument::where('nomination_id',$nomination_id)->where('doc_type',$doc_type)->first();
    return $nomination_document;
}

}