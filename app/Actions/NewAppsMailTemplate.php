<?php

namespace App\Actions;

use Carbon\Carbon;

class NewAppsMailTemplate implements HasEmailTemplateInterface  {

//The following are status keys
  // 1. Client Quoted
  // 2. Deposit Invoice
  // 3. Deposit Paid
  // 4. Payment to the Liquor Board
  // 5. Prepare New Application
  // 6. Scanned Application
  // 7. Application Lodged
  // 8. Initial Inspection
  // 9. Liquor Board Requests
  // 10. Final Inspection
  // 11. Activation Fee Requested
  // 12. Client Finalisation Invoice
  // 13. Client Paid
  // 14. Activation Fee Paid
  // 15. Licence Issued
  // 16. Licence Delivered 

  function getMailTemplate($licence){
    $template = '';

     if($licence->status == '1'){//quoted
      $template = 'Good Day '.$licence->trading_name.'.<br><br>                   
      WAITING FOR ACTUAL TEMPLATES!!! '.Carbon::parse($licence->licence_date)->format('jS').' of '.Carbon::parse($licence->licence_date)->format('F Y').'-
      attached please find a quote for us to attend to this renewal on your behalf.<br><br>
      Please ensure that payment is made before this to avoid penalties being implemented by the Liquor Board.<br><br>                    
      See our banking details below:<br>
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>
      We thank you for your continued support and look forward to assisting you with this process.<br><br>
                         
      Many thanks,
      '; 
  }elseif ($licence->status == '2') {//Client Invoiced
      $template = 'Good Day '.$licence->trading_name.'.<br><br>
      WAITING FOR ACTUAL TEMPLATES!!!.<br><br>
      <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br>
      Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
      ';

      $template = '<p>Good Day '.$licence->trading_name.'.</p>
      <p>Licence Number:&nbsp; '.$licence->licence_number.'.</p>
      <p>WAITING FOR ACTUAL TEMPLATES!!!:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</p>
      <p><br>Please note that your renewal is due.<br><br><u>
      PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:
      </u><br><br>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>Many thanks,</p>';
      
  }elseif ($licence->status == '3') {//Client Paid
      $template = 'Good Day '.$licence->trading_name.'.
      <p>Licence Number:&nbsp; '.$licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</p>
      <p>WAITING FOR ACTUAL TEMPLATES!!!Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
      <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
      as the renewal has been issued.</p>
      <br><br>Many thanks,';

      
  }elseif ($licence->status == '5') {//issued
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>WAITING FOR ACTUAL TEMPLATES!!!Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
      
  }else{
    $template = 'Waiting for templates';
      //return back()->with('error','An unknown error ocurred');
  }  

  return $template;
  }


}

