<?php

namespace App\Http\Controllers\EmailComms;

use App\Mail\TransferMailer;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class TransferEmailCommsController extends Controller
{
    //The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Transfer Documents
// 5 => Payment To The Liquor Board
// 6 => Transfer Logded
// 7 => Activation Fee Paid
// 8 => Transfer Issued
// 9 => Transfer Delivered



    public function dispatchMail(Request $request){
        try {            
            $licence = LicenceTransfer::with('licence.company')->whereSlug($request->transfer_slug)->firstOrFail();
        switch ($licence->status) {            
                case '1':                
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Client Quoted')->first();
                    break;
                case '2':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Client Invoiced')->first();
                    break;
                case '3':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Client Paid')->first();
                    break;
                case '5':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Payment To The Liquor Board')->first();
                    break;
                case '6':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Transfer Logded')->first();
                    break;
                case '7':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Activation Fee Paid')->first();
                    break;
                case '8':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Transfer Issued')->first();
                    break;
                default:
                return back()->with('error','Could not send email.');
                    break;
            }
            if(is_null($get_doc->document)){
                return back()->with('error','Mail NOT SENT!!!!.Document IS NOT YET UPLOADED.');
            }
            $recipients = [$licence->licence->company->email,$licence->licence->company->email1,$licence->licence->company->email2];
            foreach ($recipients as $recipient) {
                if(is_null($recipient)){
                    continue;
                }
                Mail::to($recipient)->send(new TransferMailer($licence, $request->mail_body));    
            }
            return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {dd($th);
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }
}
