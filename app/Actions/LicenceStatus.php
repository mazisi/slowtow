<?php

namespace App\Actions;

class LicenceStatus {

  static function getLicenceStatus($num_status) : string {
  
    switch ($num_status) {
        case '100':
           $status = 'Client Quoted';
            break;
        case '200':
            $status = 'Deposit Paid';
            break;
        case '300':
            $status = 'Client Invoiced';
            break;
        case '400':
            $status = 'Prepare New Application';
            break;
        case '500':
            $status = 'Payment To The Liquor Board';
            break;
        case '600':
            $status = 'Scanned Application';
            break;
        case '700':
            $status = 'Application Lodged';
            break;
        case '800':
            $status = 'Initial Inspection';
            break;
        case '900':
            $status = 'Liquor Board Requests';
            break;
        case '1000':
            $status = 'Final Inspection';
            break;
        case '1100':
            $status = 'Activation Fee Requested';
            break;
        case '1200':
            $status = 'Client Finalisation Invoice';
            break;
        case '1300':
            $status = 'Client Paid';
            break;
        case '1400':
            $status = 'Activation Fee Paid';
            break;
        case '1500':
            $status = 'Licence Issued';
            break;
        case '1600':
            $status = 'Licence Delivered';
            break;
        default:
            $status='';
            break;
        }
      return $status;

  
  }
}