<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nomination;
use App\Mail\RenewalMailer;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\Mail;

class EmailCommsController extends Controller
{
   public function index(Request $request){
     $month =$request->month;
            $renewals = LicenceRenewal::with('licence')->where(function($query) use($month,$request){
                $query->when($request->month, function($query) use($month,){                    
                    $query->whereHas('licence', function($query) use($month){
                        $query->whereMonth('licence_date', $month);
                    });
                })
                ->when(!is_null(request('province')), function ($query) use ($request) {
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
            })->get();

        return Inertia::render('EmailComms/EmailComm',['renewals' => $renewals]);
    }


    public function getLicenceTransfers(request $request){
        $month =$request->month;

        $transfers = LicenceTransfer::with("licence")->when($month, function($query,$month) use($request){

            $query->when($request->month, function($query) use($month,){                    
                $query->whereHas('licence', function($query) use($month){
                    $query->whereMonth('licence_date', $month);
                });
            })->when(!is_null(request('province')), function ($query) use ($request) {
                    $query->where('province',$request->province);
            });
           
            })->when(!empty(request('stage')), function ($query) use ($request) {
                $query->where('status',$request->stage);
            })->where(function($query){
                $query->where('status',1)
                ->orWhere('status',2)
                ->orWhere('status',5)
                ->orWhere('status',6)
                ->orWhere('status',7)
                ->orWhere('status',8);
            })->orderBy('status','asc')->get();


        return Inertia::render('EmailComms/Transfer',['transfers' => $transfers]);
    }

    /**
     * Get Nominations Comms.
     */
    public function getNominations(Request $request){
        $month =$request->month;

        $nominations = Nomination::with("licence")->when($month, function($query,$month) use($request){
            $query->whereHas('licence', function($query) use($month,$request){
                $query->whereMonth('licence_date', $month)
                ->when(!is_null(request('province')), function ($query) use ($request) {
                    $query->where('province',$request->province);
                });
            });
            })->when(!empty(request('stage')), function ($query) use ($request) {
                $query->where('status',$request->stage);
            })->where(function($query){
                $query->where('status',1)
                ->orWhere('status',2)
                ->orWhere('status',4)
                ->orWhere('status',7)
                ->orWhere('status',8);
            })
            ->orderBy('status','asc')->get();

        return Inertia::render('EmailComms/Nomination',['nominations' => $nominations]);
    }

    public function getMailTemplate($slug,$comm_variation){         
        switch ($comm_variation) {            
            case 'renewals': 
                $licence = LicenceRenewal::with('licence')->whereSlug($slug)->firstOrFail();   
                if($licence->status == '1'){//quoted
                    $template = 'Good Day '.$licence->licence->trading_name.'.<br><br>                   
                    Please note that your Liquor Licence is due for renewal.<br><br>
                    In aid of contributing towards an affiliation against alcohol abuse, would you like to include a donation to SANCA for an additional R1 800.
                    You will receive a Section 18A certificate for the donation which will be delivered with your renewal, which you can display along with the required liquor 
                    licence documents. Should you wish to participate in this campaign, please add R1,800 (or more) to your payment, 
                    and specify this in your response to us.<br><br>                    
                    PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:<br><br>
                    Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEON SLOTOW ASSOCIATES<br><br>
                    Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NEDBANK CENTRAL GAUTENG BRANCH<br>
                    Account No:&nbsp;&nbsp;&nbsp;&nbsp;1215489382<br>
                                       
                    Many thanks,
                    '; 
                }elseif ($licence->status == '2') {//Client Invoiced
                    $template = 'Good Day '.$licence->licence->trading_name.'.<br><br>
                    Please note that your renewal is due.<br><br>
                    <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
                    Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEON SLOTOW ASSOCIATES<br>
                    Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NEDBANK CENTRAL GAUTENG BRANCH<br>
                    Account No:&nbsp;&nbsp;&nbsp;&nbsp;1215489382<br>
                    Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
                    ';

                    $template = '<p>Good Day '.$licence->licence->trading_name.'.</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p><br>Please note that your renewal is due.<br><br><u>
                    PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:
                    </u><br><br>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEON SLOTOW ASSOCIATES<br>
                    Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NEDBANK CENTRAL GAUTENG BRANCH<br>
                    Account No:&nbsp;&nbsp;&nbsp;&nbsp;1215489382<br>Code:&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;18250500<br><br>Many thanks,</p>';
                }elseif ($licence->status == '3') {//Client Paid
                    $template = 'Good Day '.$licence->licence->trading_name.'.
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p>Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
                    <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
                    as the renewal has been issued.</p>
                    <br>Many thanks,';

                    
                }elseif ($licence->status == '5') {//issued
                   
                    $template = '<div><div>Good Day '.$licence->licence->trading_name.',<br>
                    <div>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</div>
                    <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</div>
                    </div><br><div>Please see attached copy of the latest renewal certificate.</div>
                    <br><div>The original will be delivered in due course.</div><br><div>Many thanks,</div></div>';
                    
                }else{
                    return back()->with('error','An unknown error ocurred');
                }                
                return Inertia::render('EmailComms/MailTemplate',['licence' => $licence,'template' => $template]);
                break;
            case 'alterations':
                $this->alterationMailer($slug);
                break;
            case 'transfers':
                $licence = LicenceTransfer::with('licence')->whereSlug($slug)->firstOrFail();   
                if($licence->status == '1'){//quoted
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached quotation along with the basic requirements for a transfer application.</p>
                    <p dir="ltr">Note that the Liquor Board is currently running at a delay due to COVID-19 and applications may take anywhere from 8 to 16 months to be approved. Once the application has been lodged, please ensure the necessary documents are on display in the interim to avoid any problems with inspectors.&nbsp;</p>
                    <p><strong>&nbsp;</strong></p>
                    <p dir="ltr">Many thanks,</p>
                    <p>&nbsp;</p>,
                    '; 
                }elseif ($licence->status == '2') {//Client Invoiced
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached invoice in respect of the transfer application. Note that the application will only be lodged once payment or proof of has been received.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                }elseif ($licence->status == '5') {//Payment to the Liquor Board
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached proof of payment to the Liquor Board in respect of the transfer application. Please ensure that this is on display until the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                    
                }elseif ($licence->status == '6') {//Logded
                   
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached proof of lodgement in respect of the transfer application. Please ensure that this is on display until the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p><p>&nbsp;</p>';
                }elseif ($licence->status == '7') {//Activation Fee Paid 
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">We are happy to advise you that we have been instructed by the Liquor Board to pay the activation fee for the transfer application.&nbsp; This means that the certificate will be issued imminently.&nbsp;</p>
                    <p dir="ltr">Please see attached proof of payment for the activation fee in the interim, we will advise soonest once the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>'; 
                }elseif ($licence->status == '8') {//Issued 
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
                    <p dir="ltr">The original will be delivered in due course.</p>
                    <p dir="ltr">Many thanks,</p>'; 
                }else{
                    return back()->with('error','An unknown error ocurred');
                }                
                return Inertia::render('EmailComms/TransferTemplate',['licence' => $licence,'template' => $template]);
                break;

            case 'nominations':

 
                $licence = Nomination::with('licence')->whereSlug($slug)->firstOrFail();   
                if($licence->status == '1'){//quoted
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached quotation along with the basic requirements for an appointment of managers application.</p>
                    <p dir="ltr">Note that the Liquor Board is currently running at a delay due to COVID-19 and applications may take anywhere from 6 to 12 months to be approved. Once the application has been lodged, please ensure the necessary documents are on display in the interim to avoid any problems with inspectors.&nbsp;</p>
                    <p dir="ltr"><strong>&nbsp;</strong></p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>
                    '; 
                }elseif ($licence->status == '2') {//Client Invoiced
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached invoice in respect of the appointment of managers application. Note that the application will only be lodged once payment or proof of has been received.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                }elseif ($licence->status == '4') {//Payment to the Liquor Board
                    $template = '<p dir="ltr">Good Day,</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached proof of payment in respect of the appointment of managers application. Please ensure this is on display in the interim to avoid any issues with the liquor inspectors.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                    
                }elseif ($licence->status == '7') {//Logded
                   
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">Please see attached proof of lodgement in respect of the appointment of managers application. Please ensure that this is on display in the interim until the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                }elseif ($licence->status == '8') {//issued
                    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
                    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
                    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.$licence->licence->licence_date.'</p>
                    <p dir="ltr">We are happy to inform you that the appointment of managers application has been approved! Please see attached copy of the certificate.</p>
                    <p dir="ltr">The original will be delivered in due course</p>
                    <p dir="ltr"><strong>&nbsp;</strong></p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>'; 
                
                }else{
                    return back()->with('error','An unknown error ocurred');
                }                
                return Inertia::render('EmailComms/NominationTemplate',['licence' => $licence,'template' => $template]);
                break;
            case 'new-app':
                $this->newAppMailer($slug);
                break;
    
            default:
            return back()->with('error','An error occured.Please try again.');
                break;
        }
    }


//only renewals
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 5 => Renewal Issued
    public function dispatchMail(Request $request){
        try {
            $licence = LicenceRenewal::with('licence.company')->whereSlug($request->renewal_slug)->firstOrFail();
            $stage = '';
            $renewal_stage = '';  

            switch ($licence->status) {            
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
            $get_doc = RenewalDocument::where('licence_renewal_id',$licence->id)->where('doc_type',$renewal_stage)->first();
            //check if licence already inserted in emails 
            $get_email_status = Email::where('stage', $stage)->where('model_type','renewals')->where('model_id',$licence->id)->first();

             $error_message = '';
            if(is_null($get_email_status)){
                if(is_null($get_doc)){
                    $error_message = 'Quote Document Not Uploaded';                
                    Email::insert([
                        'model_type' => 'renewals',
                        'model_id' => $licence->id,
                        'model_slug' => $licence->slug,
                        'parent_licence_slug' => $licence->licence->slug,
                        'trading_name' => $licence->licence->trading_name,
                        'status' => 'Email NOT Sent',
                        'stage' => $renewal_stage,
                        'feedback' => $error_message,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);                   
                    return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
                }
            }
            
            $email = $licence->licence->company->email;
            $email1= $licence->licence->company->email1;
            $email2 = $licence->licence->company->email2;

                if(!is_null($email)){
                    Mail::to($email)->send(new RenewalMailer($licence, $request->mail_body));   
                }elseif(is_null($email) && !is_null($email1)){
                    Mail::to($email1)->send(new RenewalMailer($licence, $request->mail_body));
                }elseif(is_null($email) && is_null($email1) && !is_null($email2)){
                    Mail::to($email2)->send(new RenewalMailer($licence, $request->mail_body));
                }else{
                    return back()->with('success','Mail NOT sent. Company does not have email addresses.');
                }
            
            //if mail sent then update is quote sent for reporting purposes
            $licence->update(['is_quote_sent' => 'true']);
           return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {throw $th;
            $error_message = 'Server Error.';
            Email::insert([
                'model_type' => 'renewals',
                'model_id' => $licence->id,
                'trading_name' => $licence->licence->trading_name,
                'model_slug' => $licence->slug,
                'parent_licence_slug' => $licence->licence->slug,
                'status' => 'Email NOT Sent',
                'stage' => $renewal_stage,
                'feedback' => $error_message,
                'created_at' => now(),
                'updated_at' => now()
            ]);  
          return back()->with('error','An error occured while sending email.');
        }
       
        
    }


}
