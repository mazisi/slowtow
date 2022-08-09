<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nomination;
use App\Mail\RenewalMailer;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\Mail;

class EmailCommsController extends Controller
{
   public function index(Request $request){
        $month =$request->month;

        $renewals = LicenceRenewal::with("licence")->when($month, function($query,$month) use($request){
            $query->whereHas('licence', function($query) use($month,$request){
                $query->whereMonth('licence_date', $month)
                ->when(!is_null(request('province')), function ($query) use ($request) {
                    $query->where('province',$request->province);
                });
            });
            })->when(!empty(request('stage')), function ($query) use ($request) {
                $query->where('status',$request->stage);
            })->orderBy('status','asc')->get();

        return Inertia::render('EmailComms/EmailComm',['renewals' => $renewals]);
    }


    /**
     * Get licence transfers..
     * The following are status keys:
        * 1 => Deposit Paid
        * 2 => Collate Transfer Details
        * 3 => Client Invoiced
        * 4 => Client Paid
        * 5 => Transfer Logded
        * 6 => Certificate Received
        *7 => Transfer Complete And Delivered
     */
    public function getLicenceTransfers(request $request){
        $month =$request->month;

        $transfers = LicenceTransfer::with("licence")->when($month, function($query,$month) use($request){
            $query->whereHas('licence', function($query) use($month,$request){
                $query->whereMonth('licence_date', $month)
                ->when(!is_null(request('province')), function ($query) use ($request) {
                    $query->where('province',$request->province);
                });
            });
            })->when(!empty(request('stage')), function ($query) use ($request) {
                $query->where('status',$request->stage);
            })->orderBy('status','asc')->get();


        return Inertia::render('EmailComms/Transfer',['transfers' => $transfers]);
    }

    /**
     * Get Nominations Comms.
     * //The following are status keys:
    * 1 => Client Invoiced
    * 2 => Nomination Paid
    * 3 => Nomination Lodged
    * 4 => Certificate Received
    * 5 => Nomination Complete And Delivered
     */
    public function getNominations(){

    $with_client_quoted_status = Nomination::with('licence')->whereStatus('1')->get();
    $with_client_invoiced_status = Nomination::with('licence')->whereStatus('2')->get();
    $with_client_paid_status = Nomination::with('licence')->whereStatus('3')->get();
    $with_payment_to_liquor_board_status = Nomination::with('licence')->whereStatus('4')->get();
    $with_documents_required_status = Nomination::with('licence')->whereStatus('6')->get();
    $with_nomination_logded_status = Nomination::with('licence')->whereStatus('7')->get();
    $with_nomination_issued_status = Nomination::with('licence')->whereStatus('8')->get();
    $with_delivered_status = Nomination::with('licence')->whereStatus('9')->get();

        return Inertia::render('EmailComms/Nomination',[
            'with_client_quoted_status' => $with_client_quoted_status,
            'with_client_invoiced_status' => $with_client_invoiced_status,
            'with_client_paid_status' => $with_client_paid_status,
            'with_payment_to_liquor_board_status' => $with_payment_to_liquor_board_status,
            'with_documents_required_status' => $with_documents_required_status,
            'with_nomination_logded_status' => $with_nomination_logded_status,
            'with_nomination_issued_status' => $with_nomination_issued_status,
            'with_delivered_status' => $with_delivered_status
        ]);
    }

    public function getMailTemplate($slug,$comm_variation){         
        switch ($comm_variation) {            
            case 'renewals': 
                $licence = LicenceRenewal::with('licence')->whereSlug($slug)->firstOrFail();   
                if($licence->status == '1'){//quoted
                    $template = 'Good Day.<br><br>                    
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
                    $template = 'Good Day.<br><br>
                    Please note that your renewal is due.<br><br>
                    <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
                    Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEON SLOTOW ASSOCIATES<br>
                    Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NEDBANK CENTRAL GAUTENG BRANCH<br>
                    Account No:&nbsp;&nbsp;&nbsp;&nbsp;1215489382<br>
                    Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
                    ';
                }elseif ($licence->status == '3') {//Client Paid
                    $template = 'Good Day,
                    <p>Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
                    <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
                    as the renewal has been issued.</p>
                    <br>Many thanks,';

                    
                }elseif ($licence->status == '5') {//issued
                   
                    $template = '<div><div>Good Day,</div><br><div>Please see attached copy of the latest renewal certificate.</div>
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

// Client Quoted with requirements 
// Client Invoiced
// Payment to the Liquor Board
// Transfer lodged 
// Activation Fee paid 
// Transfer Issued

                $licence = LicenceTransfer::with('licence')->whereSlug($slug)->firstOrFail();   
                if($licence->status == '1'){//quoted
                    $template = '<p dir="ltr">Good Day,</p>
                    <p dir="ltr">Please see attached quotation along with the basic requirements for a transfer application.</p>
                    <p dir="ltr">Note that the Liquor Board is currently running at a delay due to COVID-19 and applications may take anywhere from 8 to 16 months to be approved. Once the application has been lodged, please ensure the necessary documents are on display in the interim to avoid any problems with inspectors.&nbsp;</p>
                    <p><strong>&nbsp;</strong></p>
                    <p dir="ltr">Many thanks,</p>
                    <p>&nbsp;</p>,
                    '; 
                }elseif ($licence->status == '2') {//Client Invoiced
                    $template = '<p dir="ltr">Good Day,</p>
                    <p dir="ltr">Please see attached invoice in respect of the transfer application. Note that the application will only be lodged once payment or proof of has been received.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                }elseif ($licence->status == '5') {//Payment to the Liquor Board
                    $template = '<p dir="ltr">Good Day,</p>
                    <p dir="ltr">Please see attached proof of payment to the Liquor Board in respect of the transfer application. Please ensure that this is on display until the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>';
                    
                }elseif ($licence->status == '6') {//Logded
                   
                    $template = '<p dir="ltr">Good Day,</p>
                    <p dir="ltr">Please see attached proof of lodgement in respect of the transfer application. Please ensure that this is on display until the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p><p>&nbsp;</p>';
                }elseif ($licence->status == '7') {//Activation Fee Paid 
                    $template = '<p dir="ltr">Good Day,</p>
                    <p dir="ltr">We are happy to advise you that we have been instructed by the Liquor Board to pay the activation fee for the transfer application.&nbsp; This means that the certificate will be issued imminently.&nbsp;</p>
                    <p dir="ltr">Please see attached proof of payment for the activation fee in the interim, we will advise soonest once the application has been approved.</p>
                    <p dir="ltr">Many thanks,</p>
                    <p dir="ltr">&nbsp;</p>'; 
                }elseif ($licence->status == '8') {//Issued 
                    $template = '<p dir="ltr">Good Day,</p>
                    <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
                    <p dir="ltr">The original will be delivered in due course.</p>
                    <p dir="ltr">Many thanks,</p>'; 
                }else{
                    return back()->with('error','An unknown error ocurred');
                }                
                return Inertia::render('EmailComms/TransferTemplate',['licence' => $licence,'template' => $template]);
                break;
            case 'nominations':
                $this->nominationMailer($slug);
                return back()->with('success','Email Sent Successfully.');
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
            switch ($licence->status) {            
                case '1':                
                    $get_doc = RenewalDocument::where('licence_renewal_id',$licence->id)->where('doc_type','Client Quoted')->first();
                    break;
                case '2':
                    $get_doc = RenewalDocument::where('licence_renewal_id',$licence->id)->where('doc_type','Client Invoiced')->first();
                    break;
                case '3':
                    $get_doc = RenewalDocument::where('licence_renewal_id',$licence->id)->where('doc_type','Client Paid')->first();
                    break;
                case '5':
                    $get_doc = RenewalDocument::where('licence_renewal_id',$licence->id)->where('doc_type','Renewal Issued')->first();
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
                Mail::to($recipient)->send(new RenewalMailer($licence,$request->mail_body));    
            }
            return back()->with('success','Mail sent successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }


}
