<?php

namespace App\Actions\Wholesale;

use Carbon\Carbon;
use App\Actions\HasEmailTemplateInterface;
use App\Models\Licence;

class WholesaleNewApplicationTemplate  {
  

  function getMailTemplate($licence){
    
    $template = '';

     if($licence->status == '100'){//quoted
      $template = 'Good day,<br><br>                   
      I hope you are well.<br><br>
      Please find basic application requirements together with a quote for a tavern
      and a liquor store. Two quotes have been attached, the first quote is for the
      deposit, the second quote is the finalisation quote which you pay when your
      licence is ready.<br><br>
      Kindly note that we will first have to ensure that the premises are correctly
      zoned for a pub liquor licence to be applied for. In the interim, send us your
      complete address in order for us to establish what you are zoned for.<br><br>

      Please note that the time frame from the Liquor Board in issuing a licence
      prior to Covid-19 was 4-6 months.<br><br>

      The Liquor Board is currently running at approximately a year to 16 months
      behind for all new application and unfortunately trade cannot commence
      until the licence has been granted and issued.<br><br>
                         
      Many thanks,
      '; 
  }elseif ($licence->status == '200') {//Client Invoiced
    $template = 'Good day,<br><br>                   
    I hope you are well.<br><br>
    Please see attached invoice in respect of the new licence application.<br><br>
                       
    Many thanks,
    '; 
      
  }elseif ($licence->status == '400') {//Prepare New Application
    $template = 'Good day,<br><br>                   
    I hope you are well.<br><br>
    Please see attached proof of payment to the Liquor Board in respect of the
    new application. Please ensure that this document is on display until you are
    furnished with the proof of lodgement.<br><br>
                       
    Many thanks,
    '; 
      
  }elseif ($licence->status == '500') {//Application Submitted
     
    $template = 'Good day,<br><br>                   
                I hope you are well.<br><br>
                Please see attached proof of lodgement in respect of the new application.
                Please ensure that this is on display until the certificate has been issued.<br><br>
                       
                Many thanks,
                '; 
  }elseif ($licence->status == '600') {//Initial Application Fee

    $template = 'Good day,<br><br>                   
                I hope you are well.<br><br>
                Please note that we have received notice from the Liquor Board that initial
                inspection will be conducted for the new application.<br><br>
                Please ensure you have that no liquor is on the premises or on display as this
                will negatively impact the application.<br><br>
                        
                Many thanks,
                '; 

  }elseif ($licence->status == '700') {//Application Lodged

      $template = 'Good day,<br><br>                   
                I hope you are well.<br><br>
                Please note that the Liquor Board is looking to finalise the new licence
                application and will be conducting final inspection.<br><br>
                Please ensure that no liquor is on the premises or on display as this will
                negatively impact the application.<br><br>
                        
                Many thanks,
                '; 
  }elseif ($licence->status == '800') {//Additional Documents Request

    $template = 'Good day,<br><br>                   
              I hope you are well.<br><br>
              We are happy to advise you that the Liquor Board has granted this licence
              and has advised us to pay the activation fee.<br><br>
              Please see attached finalisation invoice, once payment or proof of has been
              received the activation fee for the licence will be paid and submitted to the
              Liquor Board.<br><br>
                      
              Many thanks,
              '; 
}elseif ($licence->status == '900') {//NLA 6 Proposed

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            Please see attached proof of payment to the Liquor Board for the activation
            fee.<br><br>
            Please ensure this is on display until the licence has been issued.<br><br>
                    
            Many thanks,
            '; 
}elseif ($licence->status == '1000') {//NLA 7 Submitted

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,
            '; 
}elseif ($licence->status == '1100') {//NLA 8 Issued

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,
            ';
}elseif ($licence->status == '1100') {//NLA 8 Issued

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,
            ';
  }elseif ($licence->status == '1100') {//NLA 8 Issued

    $template = 'Good day,<br><br>                   
              I hope you are well.<br><br>
              We are happy to advise you that the new application has been approved and
              issued! Please see attached copy of the original licence.<br><br>
              The original will be delivered in due course. Please ensure you have this
              certificate on display once received.<br><br>
                      
              Many thanks,
              '; 
}elseif ($licence->status == '1200') {//Activation Fee

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,
            ';
}elseif ($licence->status == '1300') {//NLA 9 Issued

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,';
}elseif ($licence->status == '1400') {//Original Licence

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,';
}elseif ($licence->status == '1500') {//Original Licence Delivered

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,';           
  }else{
      return back()->with('error','Error! Please check licence stages');
  }  

  return $template;
  }


}

