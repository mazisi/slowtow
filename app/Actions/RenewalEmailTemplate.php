<?php

namespace App\Actions;

use Throwable;
use App\Models\Email;
use App\Mail\RenewalMailer;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\Mail;

class RenewalEmailTemplate implements HasEmailTemplateInterface  {

    public function getView(Request $request)
    {
        return LicenceRenewal::with('licence')->where(function($query) use($request){
            $query->when($request->month, function($query) {                    
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
            ->orWhere('status',4)
            ->orWhere('status',5);
        })
        ->whereNull('deleted_at')->paginate(20)->withQueryString();
    }
 
  function getMailTemplate($renewal){

    if($renewal->status == '1'){//quoted
      $template = 'Good Day '.$renewal->licence->trading_name.'.<br><br>                   
      Please note that your Liquor Licence is due for renewal on the '.$renewal->licence->licence_date->format('d/M').'.<br><br>
      Please ensure that payment is made before this to avoid penalties being implemented by the Liquor Board.<br><br>                    
      See our banking details below:<br><br>
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br>
                         
      Many thanks,
      '; 
  }elseif ($renewal->status == '2') {//Client Invoiced
      $template = 'Good Day '.$renewal->licence->trading_name.'.<br><br>
      Please note that your renewal is due.<br><br>
      <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br>
      Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
      ';

      $template = '<p>Good Day '.$renewal->licence->trading_name.'.</p>
      <p>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$renewal->licence->licence_date.'</p>
      <p><br>Please note that your renewal is due.<br><br><u>
      PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:
      </u><br><br>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>Many thanks,</p>';
      
  }elseif ($renewal->status == '3') {//Client Paid
      $template = 'Good Day '.$renewal->licence->trading_name.'.
      <p>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$renewal->licence->licence_date.'</p>
      <p>Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
      <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
      as the renewal has been issued.</p>
      <br>Many thanks,';

      
  }elseif ($renewal->status == '5') {//issued
     
      $template = '<div><div>Good Day '.$renewal->licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$renewal->licence->licence_date.'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><div>Many thanks,</div></div>';
      
  }else{
      return back()->with('error','An unknown error ocurred');
  }  

  return $template;
  }

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
        $get_email_status = Email::where('stage', $stage)->where('model_type','renewals')->where('model_id',$renewal->id)->first();

         $error_message = '';
        if(is_null($get_email_status)){
            if(is_null($get_doc)){
                $error_message = 'Quote Document Not Uploaded';                
                $this->insertUnsentEmails($renewal, $error_message);               
                return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
            }
        }
        
            $email = $renewal->licence->company->email;
            $email1= $renewal->licence->company->email1;
            $email2 = $renewal->licence->company->email2;

            if(!is_null($email)){
                Mail::to($email)->send(new RenewalMailer($renewal, $request->mail_body));   
            }elseif(is_null($email) && !is_null($email1)){
                Mail::to($email1)->send(new RenewalMailer($renewal, $request->mail_body));
            }elseif(is_null($email) && is_null($email1) && !is_null($email2)){
                Mail::to($email2)->send(new RenewalMailer($renewal, $request->mail_body));
            }else{
                return back()->with('error','Mail NOT sent. Company does not have email addresses.');
            }
        
        //if mail sent then update is quote sent for reporting purposes
        $renewal->update(['is_quote_sent' => 'true']);

       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
       $this->insertUnsentEmails($renewal, $error_message, $renewal_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}


function insertUnsentEmails($renewal, $error_message, $renewal_stage='') : void {
    Email::insert([
        'model_type' => 'renewals',
        'model_id' => $renewal->id,
        'trading_name' => $renewal->licence->trading_name,
        'model_slug' => $renewal->slug,
        'parent_licence_slug' => $renewal->licence->slug,
        'status' => 'Email NOT Sent',
        'stage' => $renewal_stage,
        'feedback' => $error_message,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}
}

