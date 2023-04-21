<?php

namespace App\Http\Controllers\Reports;

class LicenceStatus {

  static function getLicenceStatus($num_status) : string {
  
      switch ($num_status) {
          case '1':
             $status = 'Client Quoted';
              break;
          case '2':
              $status = 'Client Invoiced';
              break;
          case '3':
              $status = 'Client Paid';
              break;
          case '4':
              $status = 'Prepare Temporary Application';
              break;
          case '5':
              $status = 'Payment To The Liquor Board';
              break;
          case '6':
              $status = 'Scanned Application';
              break;
          case '7':
              $status = 'Temporary Licence Lodged ';
              break;
          case '8':
              $status = 'Temporary Licence Issued ';
              break;
          case '9':
              $status = 'Temporary Licence Delivered';
              break;
         
          default:
              $status = '';
              break;
          }
      return $status;

  
  }
}