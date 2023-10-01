<?php

namespace App\Actions;

use Carbon\Carbon;

class AlterationEmailTemplate implements HasEmailTemplateInterface  {

     //Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered

  function getMailTemplate($renewal){
    $template = '';

     if($renewal->status == '1'){//quoted
      $template = 'Good Day '.$renewal->licence->trading_name.'.<br><br>                   
      WAITING FOR ACTUAL TEMPLATES!!! '.Carbon::parse($renewal->licence->licence_date)->format('jS').' of '.Carbon::parse($renewal->licence->licence_date)->format('F Y').'-
      attached please find a quote for us to attend to this renewal on your behalf.<br><br>
      Please ensure that payment is made before this to avoid penalties being implemented by the Liquor Board.<br><br>                    
      See our banking details below:<br>
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>
      We thank you for your continued support and look forward to assisting you with this process.<br><br>
                         
      Many thanks,
      '; 
  }elseif ($renewal->status == '2') {//Client Invoiced
      $template = 'Good Day '.$renewal->licence->trading_name.'.<br><br>
      WAITING FOR ACTUAL TEMPLATES!!!.<br><br>
      <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br>
      Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
      ';

      $template = '<p>Good Day '.$renewal->licence->trading_name.'.</p>
      <p>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</p>
      <p>WAITING FOR ACTUAL TEMPLATES!!!:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($renewal->licence->licence_date)->format('d/m').'</p>
      <p><br>Please note that your renewal is due.<br><br><u>
      PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:
      </u><br><br>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>Many thanks,</p>';
      
  }elseif ($renewal->status == '3') {//Client Paid
      $template = 'Good Day '.$renewal->licence->trading_name.'.
      <p>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($renewal->licence->licence_date)->format('d/m').'</p>
      <p>WAITING FOR ACTUAL TEMPLATES!!!Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
      <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
      as the renewal has been issued.</p>
      <br><br>Many thanks,';

      
  }elseif ($renewal->status == '5') {//issued
     
      $template = '<div><div>Good Day '.$renewal->licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($renewal->licence->licence_date)->format('d/m').'</div>
      </div><br><div>WAITING FOR ACTUAL TEMPLATES!!!Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
      
  }else{
    $template = 'Waiting for templates';
      //return back()->with('error','An unknown error ocurred');
  }  

  return $template;
  }


}

