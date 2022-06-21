<?php

namespace App\Http\Controllers\Traits;

use App\Mail\EmailCommsMailer;
use App\Models\LicenceRenewal;
use Illuminate\Support\Facades\Mail;

trait RenewalMailer{


  public function renewalMailer($slug){

      $renewal_received_template = 'This mail is to confirm that your invoice Renewal has been recevied Received,';
      $client_invoiced_template = 'This mail is Client Invoiced,';
      $client_paid_template = 'This mail is Client Paid,';
      $renewal_processed_template = 'This mail is Renewal Processed,';
      $renewal_complete_template = 'This mail is to confirm that your invoice Renewal has been Complete,';

    $renewal = LicenceRenewal::whereSlug($slug)->firstOrFail();    
      
      switch ($renewal->status) {
        case '1':
          RenewalMailer::sendRenewalMail($renewal_received_template,'Renewal Received');
          $renewal->update(['status' => '2']);
          break;
        case '2':
          RenewalMailer::sendRenewalMail($client_invoiced_template,'Invoice Client');
          $renewal->update(['status' => '3']);
          break;
        case '3':
          RenewalMailer::sendRenewalMail($client_paid_template,'Payment Confirmaion');
          $renewal->update(['status' => '4']);
          break;
        case '4':
          RenewalMailer::sendRenewalMail($renewal_processed_template,'Renewal Processed');
          $renewal->update(['status' => '5']);
          break;
        case '5':
          RenewalMailer::sendRenewalMail($renewal_complete_template,'Renewal Complete');
          break;
        
        default:
          # code...
          break;
      }
    
}

//The following are status keys
// 1 => Renewal Received
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Renewal Processed
// 5 => Renewal Complete & Delivered


public static function sendRenewalMail($message,$subject){
  $mailables = [
    'message' => $message,
    'subject' => $subject
  ];
  Mail::to('mazisimsebele18@gmail.com')->send(new EmailCommsMailer($mailables));
}


}