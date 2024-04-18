<?php

namespace App\Actions;

use Carbon\Carbon;

class WholesaleTransferTemplate{

  function getMailTemplate($licence){
    $template = '';
    
    if($licence->status == '100'){//quoted
      $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached quotation along with the basic requirements for a transfer application.</p>
      <p dir="ltr">Note that the Liquor Board is currently running at a delay due to COVID-19 and applications may take anywhere from 8 to 16 months to be approved. Once the application has been lodged, please ensure the necessary documents are on display in the interim to avoid any problems with inspectors.&nbsp;</p>
      <p><strong>&nbsp;</strong></p>
      <p dir="ltr">Many thanks,</p>
      <p>&nbsp;</p>,
      '; 
  }elseif ($licence->status == '200') {//Client Invoiced
      $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached invoice in respect of the transfer application. Note that the application will only be lodged once payment or proof of has been received.</p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>';
  }elseif ($licence->status == '300') {//Client Paid
      $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached proof of payment to the Liquor Board in respect of the transfer application. Please ensure that this is on display until the application has been approved.</p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>';
      
  }elseif ($licence->status == '400') {//Transfer Documents Preparation
     
      $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached proof of lodgement in respect of the transfer application. Please ensure that this is on display until the application has been approved.</p>
      <p dir="ltr">Many thanks,</p><p>&nbsp;</p>';

  }elseif ($licence->status == '500') {//Proof of Payment to the National Liquor Authority
      $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">We are happy to advise you that we have been instructed by the Liquor Board to pay the activation fee for the transfer application.&nbsp; This means that the certificate will be issued imminently.&nbsp;</p>
      <p dir="ltr">Please see attached proof of payment for the activation fee in the interim, we will advise soonest once the application has been approved.</p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>'; 

  }elseif ($licence->status == '600') {//Scanned Application
      $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
      <p dir="ltr">The original will be delivered in due course.</p>
      <p dir="ltr">Many thanks,</p>';

  }elseif ($licence->status == '700') {//Application Lodged
    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
    <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
    <p dir="ltr">The original will be delivered in due course.</p>
    <p dir="ltr">Many thanks,</p>';
  
  }elseif ($licence->status == '800') {//Additional Documents Requested
    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
    <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
    <p dir="ltr">The original will be delivered in due course.</p>
    <p dir="ltr">Many thanks,</p>';

  }elseif ($licence->status == '900') {//Transfer Certificate Issued
    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
    <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
    <p dir="ltr">The original will be delivered in due course.</p>
    <p dir="ltr">Many thanks,</p>';

  }elseif ($licence->status == '1000') {//Transfer Certificate  Delivered
    $template = '<p dir="ltr">Good Day '.$licence->licence->trading_name.',</p>
    <p>Licence Number:&nbsp; '.$licence->licence->licence_number.'.</p>
    <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence->licence_date)->format('d/m').'</p>
    <p dir="ltr">We are happy to advise you that the transfer application has been approved! Please see attached copy of the transfer certificate.</p>
    <p dir="ltr">The original will be delivered in due course.</p>
    <p dir="ltr">Many thanks,</p>';
  }else{
    $template = 'This transfer may belong to wholesale...And we dont have wholesale templates yet';
  }
  return $template;         
  }
}