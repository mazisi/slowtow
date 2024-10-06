<?php

namespace App\Actions;

class WholesaLeLicenceStatus {

  static function getLicenceStatus($num_status) : string {
  
    switch ($num_status) {
        case '100':
           $status = 'Client Quoted';
            break;
        case '200':
            $status = 'Deposit Invoiced';
            break;
        case '300':
            $status = 'Deposit Paid';
            break;
        case '400':
            $status = 'Prepare New Application';
            break;
        case '500':
            $status = 'Application Submitted';
            break;
        case '600':
            $status = 'Initial Application Fee';
            break;
        case '700':
            $status = 'Application Lodged';
            break;
        case '800':
            $status = 'Additional Documents Request';
            break;
        case '900':
            $status = 'NLA 6 Proposed';
            break;
        case '1000':
            $status = 'NLA 7 Submitted';
            break;
        case '1100':
            $status = 'NLA 8 Issued';
            break;
        case '1200':
            $status = 'Activation Fee';
            break;
        case '1300':
            $status = 'NLA 9 Issued';
            break;
        case '1400':
            $status = 'Original Licence';
            break;
        case '1500':
            $status = 'Original Licence Delivered';
            break;
        default:
            $status='';
            break;
        }
      return $status;

  
  }
}