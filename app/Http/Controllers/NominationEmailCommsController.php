<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Email;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Mail\NominationMailer;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\Mail;

class NominationEmailCommsController extends Controller
{


    public function getNominations(Request $request){

        $nominations = Nomination::with("licence")->when($request, function($query) use($request){
            $query->when(request('month'), function($query) {                    
                $query->whereHas('licence', function($query){
                    $query->whereMonth('licence_date', request('month'))
                    ->whereNull('deleted_at');
                });
            })
            ->when(request('province'), function ($query) use ($request) {
                $query->whereHas('licence', function($query) use($request){
                    $query->where('province',$request->province);
                });
               
            })
          ->when(!empty(request('stage')), function ($query) use ($request) {
            $query->where('status',$request->stage);
        });
        })->where(function($query){
            $query->where('status',1)
            ->orWhere('status',2)
            ->orWhere('status',4)
            ->orWhere('status',7)
            ->orWhere('status',8);
        })->whereNull('deleted_at')
        ->orderBy('status','asc')->paginate(20)->withQueryString();

    return Inertia::render('EmailComms/Nomination',['nominations' => $nominations]);
}


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
            //check if licence already inserted in emails 
            $get_email_status = Email::where('stage', $stage)->where('model_type','nominations')->where('model_id',$nomination->id)->first();

             $error_message = '';
            if(is_null($get_email_status)){
                if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
                    $this->insertUnsentEmails($nomination, $error_message);                
                    return back()->with('error',' Quote Document is not yet uploaded.');
                }
            }
            
            $email = $nomination->licence->company->email;
            $email1= $nomination->licence->company->email1;
            $email2 = $nomination->licence->company->email2;

            //  Mail::to('mazisimsebele18@gmail.com')
            //     ->cc(['mazisi@mrnlabs.com', 'test@gmail.com'])->send(new NominationMailer($nomination, $request->mail_body));


                 if(! is_null($email) || ! empty($email)){
                Mail::to($email)
                ->cc([$email1,'info@slotow.co.za'])
                ->bcc([$email2,'sales@slotow.co.za'])->send(new NominationMailer($nomination, $request->mail_body)); 
            }
            elseif((is_null($email) || empty($email))){
                Mail::to($email1)->cc([$email2, 'info@slotow.co.za'])->bcc('sales@slotow.co.za')->send(new NominationMailer($nomination, $request->mail_body));
            }
            
            elseif(!$email && !$email1 && (! is_null($email2) || !empty($email2))){
                    Mail::to($email2)->cc('info@slotow.co.za')->bcc('sales@slotow.co.za')->send(new NominationMailer($nomination, $request->mail_body));
            }else{
                return back()->with('error','Mail NOT sent. Company does not have email addresses.');
            }
             
            return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {
            $error_message = 'Server Error.';
            $this->insertUnsentEmails($nomination, $error_message, $stage);
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }

    function getDocumentType($nomination_id, $doc_type){
        $nomination_document = NominationDocument::where('nomination_id',$nomination_id)->where('doc_type',$doc_type)->first();
        return $nomination_document;
    }

    function insertUnsentEmails($nomination, $error_message,$stage='') : void {
        Email::insert([
            'model_type' => 'transfers',
            'model_id' => $nomination->id,
            'trading_name' => $nomination->licence->trading_name,
            'model_slug' => $nomination->slug,
            'parent_licence_slug' => $nomination->licence->slug,
            'status' => 'Email NOT Sent',
            'stage' => $stage,
            'feedback' => $error_message,
            'created_at' => now(),
            'updated_at' => now()
        ]);  
    }
}
