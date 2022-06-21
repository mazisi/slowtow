<?php

namespace App\Http\Controllers\Traits;

use App\Models\Nomination;
use App\Mail\EmailCommsMailer;
use Illuminate\Support\Facades\Mail;

trait NominationMailer{

  //The following are status keys:
// 1 => Client Invoiced
// 2 => Nomination Paid
// 3 => Nomination Lodged
// 4 => Certificate Received
// 5 => Nomination Complete And Delivered

  public function nominationMailer($slug){

    $nomination_paid_template = 'This mail is to confirm that your nomiantion has been Paid,';
    $client_invoiced_template = 'This mail is Client Invoiced,';
    $transfer_logded_template = 'This mail is transfer logded Processed,';
    $certificate_received_template = 'This mail is to confirm that your certificate has been received,';
    $nomination_complete_template = 'This mail is to confirm that your invoice nomination has been Complete,';

    $nomi = Nomination::whereSlug($slug)->first();
    
    switch ($nomi->status) {
      case '1':
        NominationMailer::sendNominationMail($client_invoiced_template,'Client Invoiced');
        $nomi->update(['status' => '2']);
        break;
      case '2':
        NominationMailer::sendNominationMail($nomination_paid_template,'Nomination Paid');
        $nomi->update(['status' => '3']);
        break;
      case '3':
        NominationMailer::sendNominationMail($nomination_lodged_template,'Nomination Lodged');
        $nomi->update(['status' => '4']);
        break;
      case '4':
        NominationMailer::sendNominationMail($certificate_received_template,'Certificate Received');
        $nomi->update(['status' => '5']);
        break;
      case '5':
        NominationMailer::sendNominationMail($nomination_complete_template,'Nomination Complete And Delivered');
        $nomi->update(['status' => '6']);
        break;
      
      default:
        # code...
        break;
    }

}

/**
 * Mail Dispatcher.
 */

public static function sendNominationMail($message,$subject){
  $mailables = [
    'message' => $message,
    'subject' => $subject
  ];
  Mail::to('mazisimsebele18@gmail.com')->send(new EmailCommsMailer($mailables));
}
}