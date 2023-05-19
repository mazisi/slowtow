<?php

namespace App\Http\Controllers\EmailComms;

use Inertia\Inertia;
use App\Models\Email;
use App\Mail\TransferMailer;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class TransferEmailCommsController extends Controller
{

    public function getLicenceTransfers(request $request){

        $transfers = LicenceTransfer::with("licence")->when($request, function($query) use($request){
            $query->when(request('month'), function($query) {                    
                $query->whereHas('licence', function($query) {
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
            ->orWhere('status',5)
            ->orWhere('status',6)
            ->orWhere('status',7)
            ->orWhere('status',8);
        })->whereNull('deleted_at')
        ->orderBy('status','asc')
           ->paginate(20)->withQueryString();


    return Inertia::render('EmailComms/Transfer',['transfers' => $transfers]);
}


//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Prepare Transfer Application
// 5 => Payment To The Liquor Board
// 6 => Scanned Application
// 7 => Application Logded
// 8 => Activation Fee Paid
// 9 => Transfer Issued
// 10 => Transfer Delivered



    public function dispatchTransferMail(Request $request){
        try {            
            $transfer = LicenceTransfer::with('licence.company')->whereSlug($request->transfer_slug)->firstOrFail();
        switch ($transfer->status) {            
                case '1':                
                    $stage = 'Client Quoted';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '2':
                    $stage = 'Client Invoiced';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '3':
                    $stage = 'Client Paid';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '5':
                    $stage = 'Payment To The Liquor Board';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;

                case '6':
                    $stage = 'Scanned Application';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '7':
                    $stage = 'Transfer Logded';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '8':
                    $stage = 'Activation Fee Paid';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '9':
                    $stage = 'Transfer Issued';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '10':
                    $stage = 'Transfer Delivered';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                default:
                   return back()->with('error','Could not send email.');
               
            }
           
            //check if licence already inserted in emails 
            $get_email_status = Email::where('stage', $stage)->where('model_type','transfers')->where('model_id',$transfer->id)->first();

             $error_message = '';
            if(is_null($get_email_status)){
                if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
                    $this->insertUnsentEmails($transfer, $error_message);                   
                    return back()->with('error','Quote Document not yet uploaded.');
                }
            }
            $email = $transfer->licence->company->email;
            $email1= $transfer->licence->company->email1;
            $email2 = $transfer->licence->company->email2;

                if(!is_null($email)){
                    Mail::to($email)->send(new TransferMailer($transfer, $request->mail_body));   
                }elseif(is_null($email) && !is_null($email1)){
                    Mail::to($email1)->send(new TransferMailer($transfer, $request->mail_body));
                }elseif(is_null($email) && is_null($email1) && !is_null($email2)){
                    Mail::to($email2)->send(new TransferMailer($transfer, $request->mail_body));
                }else{
                    return back()->with('error','Mail NOT sent. Company does not have email addresses.');
                }


        //if email is resent successfully delete model from emails table
        $is_email_resent = Email::where('stage', $stage)->where('model_type','transfers')->where('model_id',$transfer->id)->first();
        (is_null($is_email_resent)) ? '' : $is_email_resent->delete() ;
         return back()->with('success','Mail sent successfully.');


        } catch (\Throwable $th) {
            $error_message = '500....Server Error.';
            $this->insertUnsentEmails($transfer, $error_message, $stage);  
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }

    function getDocumentType($transfer, $doc_type){
        $tranfer_document = TransferDocument::where('licence_transfer_id',$transfer->id)->where('doc_type',$doc_type)->first();
        return $tranfer_document;
    }

    function insertUnsentEmails($transfer, $error_message, $stage='') : void {
        Email::insert([
            'model_type' => 'transfers',
            'model_id' => $transfer->id,
            'trading_name' => $transfer->licence->trading_name,
            'model_slug' => $transfer->slug,
            'parent_licence_slug' => $transfer->licence->slug,
            'status' => 'Email NOT Sent',
            'stage' => $stage,
            'feedback' => $error_message,
            'created_at' => now(),
            'updated_at' => now()
        ]);  
    }
}
