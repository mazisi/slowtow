<?php

namespace App\Http\Controllers\Traits;

use App\Mail\EmailCommsMailer;
use App\Models\LicenceTransfer;
use Illuminate\Support\Facades\Mail;

trait TransferMailer{

  //The following are status keys
// 1 => Deposit Paid
// 2 => Collate Transfer Details
// 3 => Client Invoiced
// 4 => Client Paid
// 5 => Transfer Logded
// 6 => Certificate Received
//7 => Transfer Complete And Delivered

  public function transferMailer($slug){
    $deposit_paid_template = 'This mail is to confirm that your Deposit has been recevied Received,';
    $client_invoiced_template = 'This mail is Client Invoiced,';
    $client_paid_template = 'This mail is Client Paid,';
    $transfer_logded_template = 'This mail is transfer logded Processed,';
    $certificate_received_template = 'This mail is to confirm that your certificate has been received,';
    $transfer_complete_template = 'This mail is to confirm that your invoice transfer has been Complete,';
    

    $transfer = LicenceTransfer::whereSlug($slug)->first();    
    //update status
    switch ($transfer->status) {
      case '1':
        TransferMailer::sendTransferMail($deposit_paid_template,'Deposit Paid');
        $transfer->update(['status' => '3']);
        break;
      case '3':
        TransferMailer::sendTransferMail($client_invoiced_template,'Client Invoiced');
        $transfer->update(['status' => '4']);
        break;
      case '4':
        TransferMailer::sendTransferMail($client_paid_template,'Client Paid');
        $transfer->update(['status' => '5']);
        break;
      case '5':
        TransferMailer::sendTransferMail($transfer_logded_template,'Transfer Logded');
        $transfer->update(['status' => '6']);
        break;
      case '6':
        TransferMailer::sendTransferMail($certificate_received_template,'Certificate Received');
        $transfer->update(['status' => '7']);
        break;
      case '7':
        TransferMailer::sendTransferMail($transfer_complete_template,'Transfer Complete ');
        break;
      
      default:
        # code...
        break;
    }

}
/**
 * Mail Dispatcher.
 */

public static function sendTransferMail($message,$subject){
  $mailables = [
    'message' => $message,
    'subject' => $subject
  ];
  Mail::to('mazisimsebele18@gmail.com')->send(new EmailCommsMailer($mailables));
}

}