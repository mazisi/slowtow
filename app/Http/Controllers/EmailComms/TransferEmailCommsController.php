<?php

namespace App\Http\Controllers\EmailComms;

use App\Models\Email;
use App\Mail\TransferMailer;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class TransferEmailCommsController extends Controller
{
    //The following are status keys
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



    public function dispatchMail(Request $request){
        try {            
            $licence = LicenceTransfer::with('licence.company')->whereSlug($request->transfer_slug)->firstOrFail();
        switch ($licence->status) {            
                case '1':                
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Client Quoted')->first();
                    $stage = 'Client Quoted';
                    break;
                case '2':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Client Invoiced')->first();
                    $stage = 'Client Invoiced';
                    break;
                case '3':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Client Paid')->first();
                    $stage = 'Client Paid';
                    break;
                case '5':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Payment To The Liquor Board')->first();
                    $stage = 'Payment To The Liquor Board';
                    break;

                case '6':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Scanned Application')->first();
                    $stage = 'Scanned Application';
                    break;
                case '7':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Transfer Logded')->first();
                    $stage = 'Transfer Logded';
                    break;
                case '8':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Activation Fee Paid')->first();
                    $stage = 'Activation Fee Paid';
                    break;
                case '9':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Transfer Issued')->first();
                    $stage = 'Transfer Issued';
                    break;
                case '10':
                    $get_doc = TransferDocument::where('licence_transfer_id',$licence->id)->where('doc_type','Transfer Delivered')->first();
                    $stage = 'Transfer Delivered';
                    break;
                default:
                   return back()->with('error','Could not send email.');
               
            }
           
            //check if licence already inserted in emails 
            $get_email_status = Email::where('stage', $stage)->where('model_type','transfers')->where('model_id',$licence->id)->first();

             $error_message = '';
            if(is_null($get_email_status)){
                if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
                    Email::insert([
                        'model_type' => 'transfers',
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
                    return back()->with('error','Quote Document not yet uploaded.');
                }
            }
            $email = $licence->licence->company->email;
            $email1= $licence->licence->company->email1;
            $email2 = $licence->licence->company->email2;

                if(!is_null($email)){
                    Mail::to($email)->send(new TransferMailer($licence, $request->mail_body));   
                }elseif(is_null($email) && !is_null($email1)){
                    Mail::to($email1)->send(new TransferMailer($licence, $request->mail_body));
                }elseif(is_null($email) && is_null($email1) && !is_null($email2)){
                    Mail::to($email2)->send(new TransferMailer($licence, $request->mail_body));
                }else{
                    return back()->with('error','Mail NOT sent. Company does not have email addresses.');
                }


        //if email is resent successfully delete model from emails table
        $is_email_resent = Email::where('stage', $stage)->where('model_type','transfers')->where('model_id',$licence->id)->first();
        (is_null($is_email_resent)) ? '' : $is_email_resent->delete() ;
         return back()->with('success','Mail sent successfully.');


        } catch (\Throwable $th) {
            $error_message = '500....Server Error.';
            Email::insert([
                'model_type' => 'transfers',
                'model_id' => $licence->id,
                'trading_name' => $licence->licence->trading_name,
                'model_slug' => $licence->slug,
                'parent_licence_slug' => $licence->licence->slug,
                'status' => 'Email NOT Sent',
                'stage' => $stage,
                'feedback' => $error_message,
                'created_at' => now(),
                'updated_at' => now()
            ]);  
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }
}
