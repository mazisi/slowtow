<?php

namespace App\Actions\Wholesale;

use Carbon\Carbon;

class WholesaleRenewalTemplate  {
  

  function getMailTemplate($licence){
    $template = '';

     if($licence->status == '100'){//Renewal Notice Received
      $template = 'Good Day '.$licence->trading_name.'.<br><br>                   
      Please note that your Liquor Licence is due for renewal on the '.Carbon::parse($licence->licence_date)->format('jS').' of '.Carbon::parse($licence->licence_date)->format('F Y').'-
      attached please find a quote for us to attend to this renewal on your behalf.<br><br>
      Please ensure that payment is made before this to avoid penalties being implemented by the Liquor Board.<br><br>                    
      See our banking details below:<br>
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>
      We thank you for your continued support and look forward to assisting you with this process.<br><br>
                         
      Many thanks,
      '; 
  }elseif ($licence->status == '200') {//Turnover Information Requested
      $template = 'Good Day '.$licence->trading_name.'.<br><br>
      Please note that your renewal is due.<br><br>
      <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br>
      Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
      ';

      $template = '<p>Good Day '.$licence->trading_name.'.</p>
      <p>Licence Number:&nbsp; '.$licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</p>
      <p><br>Please note that your renewal is due.<br><br><u>
      PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:
      </u><br><br>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>Many thanks,</p>';
      
  }elseif ($licence->status == '300') {//Turnover Information Received
      $template = 'Good Day '.$licence->trading_name.'.
      <p>Licence Number:&nbsp; '.$licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</p>
      <p>Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
      <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
      as the renewal has been issued.</p>
      <br><br>Many thanks,';

      
  }elseif ($licence->status == '400') {//Annual Return Submitted
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';

    }elseif ($licence->status == '500') {//Client Invoiced'
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';

    }elseif ($licence->status == '600') {//Client Paid
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '700') {//Payment to the National Liquor Authority
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '800') {//Annual Return Submitted
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '900') {//Renewal Forms Sent
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1000') {//Renewal Forms Preparation
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1100') {//Renewal Submitted
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1200') {//Additional Documents/Information Requested
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1300') {//Renewal Pending QA
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1400') {//Renewal Awaiting Sign Off
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1500') {//Renewal Approved
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
    }elseif ($licence->status == '1600') {//NLA 33 Delivered
     
      $template = '<div><div>Good Day '.$licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
      
  }else{
      return back()->with('error','An unknown error ocurred');
  }  

  return $template;
  }


}

