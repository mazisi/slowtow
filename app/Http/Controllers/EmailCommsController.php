<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;
use App\Http\Controllers\Traits\RenewalMailer;
use App\Http\Controllers\Traits\TransferMailer;
use App\Http\Controllers\Traits\AlterationMailer;
use App\Http\Controllers\Traits\NominationMailer;

class EmailCommsController extends Controller
{
    use RenewalMailer, TransferMailer, AlterationMailer, NominationMailer;
    /**
     * Return licence renewal..
     * Renewals bcoz they are on default tag..
     * 
       * The following are status keys:
       *  1 => Client Quoted
        * 2 => Client Invoiced
        * 3 => Client Paid
        * 4 => Payment To Liquor Board
        * 5 => Renewal Complete
     */
   
    public function index(Request $request){
        $month =$request->month;

        $with_renewal_received_status = LicenceRenewal::with("licence")->when($month, function($query,$month) use($request){
            $query->whereHas('licence', function($query) use($month,$request){
                $query->whereMonth('licence_date', $month)
                ->when(!is_null(request('province')), function ($query) use ($request) {
                    $query->where('province',$request->province);
                });
            });
            })->where('status','1')->get();
    
       $with_client_invoiced_status = LicenceRenewal::with("licence")->when($month, function($query,$month) use($request){
        $query->whereHas('licence', function($query) use($month,$request){
            $query->whereMonth('licence_date', $month)
            ->when(!is_null(request('province')), function ($query) use ($request) {
                $query->where('province',$request->province);
            });
        });
            })->where('status','2')->get();


            $with_client_paid_status = LicenceRenewal::with("licence")->when($month, function($query,$month) use($request){
                $query->whereHas('licence', function($query) use($month,$request){
                    $query->whereMonth('licence_date', $month)
                    ->when(!is_null(request('province')), function ($query) use ($request) {
                        $query->where('province',$request->province);
                    });
                });
            })->where('status','3')->get();

            $with_renewal_processed_status = LicenceRenewal::with("licence")->when($month, function($query,$month) use($request){
                $query->whereHas('licence', function($query) use($month,$request){
                    $query->whereMonth('licence_date', $month)
                    ->when(!is_null(request('province')), function ($query) use ($request) {
                        $query->where('province',$request->province);
                    });
                });
            })->where('status','4')->get();

            $with_renewal_complete_status = LicenceRenewal::with("licence")->when($month, function($query,$month) use($request){
                $query->whereHas('licence', function($query) use($month,$request){
                    $query->whereMonth('licence_date', $month)
                    ->when(!is_null(request('province')), function ($query) use ($request) {
                        $query->where('province',$request->province);
                    });
                });
            })->where('status','5')->get();
   
        
        return Inertia::render('EmailComms/EmailComm',[

            'with_renewal_received_status' => $with_renewal_received_status,
            'with_client_invoiced_status' => $with_client_invoiced_status,
            'with_client_paid_status' => $with_client_paid_status,
            'with_renewal_processed_status' => $with_renewal_processed_status,
            'with_renewal_complete_status' => $with_renewal_complete_status
        ]);
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
    public function getLicenceTransfers(){

        $with_deposit_paid_status = LicenceTransfer::with('licence')->whereStatus('1')->get();
        $with_client_invoiced_status = LicenceTransfer::with('licence')->whereStatus('3')->get();
        $with_get_client_paid_status = LicenceTransfer::with('licence')->whereStatus('4')->get();
        $with_transfer_logded_status = LicenceTransfer::with('licence')->whereStatus('5')->get();
        $with_certificate_received_status = LicenceTransfer::with('licence')->whereStatus('6')->get();
        $with_complete_status = LicenceTransfer::with('licence')->whereStatus('7')->get();

        return Inertia::render('EmailComms/Transfer',[
            'with_deposit_paid_status' => $with_deposit_paid_status,
            'with_client_invoiced_status' => $with_client_invoiced_status,
            'with_get_client_paid_status' => $with_get_client_paid_status,
            'with_transfer_logded_status' => $with_transfer_logded_status,
            'with_certificate_received_status' => $with_certificate_received_status,
            'with_complete_status' => $with_complete_status
        ]);
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

        $with_client_invoiced_status = Nomination::with('licence')->whereStatus('1')->get();
        $with_nomination_paid_status = Nomination::with('licence')->whereStatus('2')->get();
        $with_nomination_logded_status = Nomination::with('licence')->whereStatus('3')->get();
        $with_certificate_received_status = Nomination::with('licence')->whereStatus('4')->get();
        $with_complete_status = Nomination::with('licence')->whereStatus('5')->get();

        return Inertia::render('EmailComms/Nomination',[
            'with_client_invoiced_status' => $with_client_invoiced_status,
            'with_nomination_paid_status' => $with_nomination_paid_status,
            'with_nomination_logded_status' => $with_nomination_logded_status,
            'with_certificate_received_status' => $with_certificate_received_status,
            'with_complete_status' => $with_complete_status
        ]);
    }

    public function processMail($slug,$licence_variation){

        switch ($licence_variation) {
            
            case 'renewals':
                $this->renewalMailer($slug);
                return back()->with('success','Email Sent Successfully.');
                break;
            case 'alterations':
                $this->alterationMailer($slug);
                break;
            case 'transfers':
                $this->transferMailer($slug);
                return back()->with('success','Email Sent Successfully.');
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

}
