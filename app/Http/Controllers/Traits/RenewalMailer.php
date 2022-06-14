<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\LicenceRenewal;

trait RenewalMailer{

  public function renewalMailer($slug){
    $renewal = LicenceRenewal::whereSlug($slug)->first();
    
    //update status
      if($renewal->status == 'Invoiced'){

        $renewal->update(['status' => 'Paid']);
        $this->sendMail();

      }elseif ($renewal->status == 'Paid') {

        $renewal->update(['status' => 'Get Client Docs']);

      }elseif ($renewal->status == 'Get Client Docs') {

        $renewal->update(['status' => 'Awaiting Liquor Board']);

      }elseif ($renewal->status == 'Awaiting Liquor Board') {

        $renewal->update(['status' => 'Issued']);

      }elseif ($renewal->status == 'Issued') {

        $renewal->update(['status' => 'Complete']);

      }elseif ($renewal->status == 'Complete') {

        $renewal->update(['status' => 'Paid']);
      }
    


}
}