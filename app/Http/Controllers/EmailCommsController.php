<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;
use App\Http\Controllers\Traits\RenewalMailer;
use App\Http\Controllers\Traits\transferMailer;
use App\Http\Controllers\Traits\AlterationMailer;
use App\Http\Controllers\Traits\NominationMailer;

class EmailCommsController extends Controller
{
    use RenewalMailer, transferMailer, AlterationMailer, NominationMailer;
    /**
     * Return licence renewal..
     * Renewals bcoz they are on default tag..
     */
    public function index(){
        $with_invoiced_statuses = LicenceRenewal::with('licence')->where('status','Renewal Received')->get();
        $with_paid_statuses = LicenceRenewal::with('licence')->where('status','Paid')->get();
        $with_get_client_docs_statuses = LicenceRenewal::with('licence')->where('status','Get Client Docs')->get();
        $with_awaiting_liquor_board_statuses = LicenceRenewal::with('licence')->where('status','Awaiting Liquor Board')->get();
        $with_issued_statuses = LicenceRenewal::with('licence')->where('status','Issued')->get();
        $with_complete_statuses = LicenceRenewal::with('licence')->where('status','Complete')->get();
        
        return Inertia::render('EmailComms/EmailComm',[

            'with_invoiced_statuses' => $with_invoiced_statuses,
            'with_paid_statuses' => $with_paid_statuses,
            'with_get_client_docs_statuses' => $with_get_client_docs_statuses,
            'with_awaiting_liquor_board_statuses' => $with_awaiting_liquor_board_statuses,
            'with_issued_statuses' => $with_issued_statuses,
            'with_complete_statuses' => $with_complete_statuses
        ]);
    }

    /**
     * Get licence transfers..
     */
    public function getLicenceTransfers(){

        $with_transfer_statuses = LicenceTransfer::with('licence')->where('status','Invoiced')->get();
        $with_paid_statuses = LicenceTransfer::with('licence')->where('status','Paid')->get();
        $with_get_client_docs_statuses = LicenceTransfer::with('licence')->where('status','Get Client Docs')->get();
        $with_awaiting_liquor_board_statuses = LicenceTransfer::with('licence')->where('status','Awaiting Liquor Board')->get();
        $with_issued_statuses = LicenceTransfer::with('licence')->where('status','Issued')->get();
        $with_complete_statuses = LicenceTransfer::with('licence')->where('status','Complete')->get();

        return Inertia::render('EmailComms/Transfer',[
            'with_transfer_statuses' => $with_transfer_statuses,
            'with_paid_statuses' => $with_paid_statuses,
            'with_get_client_docs_statuses' => $with_get_client_docs_statuses,
            'with_awaiting_liquor_board_statuses' => $with_awaiting_liquor_board_statuses,
            'with_issued_statuses' => $with_issued_statuses,
            'with_complete_statuses' => $with_complete_statuses
        ]);
    }

    public function processMail($slug,$licence_variation){

        switch ($licence_variation) {
            
            case 'renewals':
                $this->renewalMailer($slug);
                break;
            case 'alterations':
                $this->alterationMailer($slug);
                break;
            case 'transfers':
                $this->transferMailer($slug);
                break;
            case 'nominations':
                $this->nominationMailer($slug);
                break;
            case 'new-app':
                $this->newAppMailer($slug);
                break;
    
            default:
                # code...
                break;
        }
    }
    /**
     * Send nomination mail..
     */

    public function sendMail(){
        # code...
    }
}
