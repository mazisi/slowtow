<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\LicenceTransfer;

trait TransferMailer{

  public function transferMailer($slug){
    $transfer = LicenceTransfer::whereSlug($slug)->first();    
    //update status
      if($transfer->status == 'Invoiced'){

        $transfer->update(['status' => 'Paid']);
        $this->sendMail();

      }elseif ($transfer->status == 'Paid') {

        $transfer->update(['status' => 'Get Client Docs']);

      }elseif ($transfer->status == 'Get Client Docs') {

        $transfer->update(['status' => 'Awaiting Liquor Board']);

      }elseif ($transfer->status == 'Awaiting Liquor Board') {

        $transfer->update(['status' => 'Issued']);

      }elseif ($transfer->status == 'Issued') {

        $transfer->update(['status' => 'Complete']);

      }elseif ($transfer->status == 'Complete') {

        $transfer->update(['status' => 'Paid']);
      }
    


}
}