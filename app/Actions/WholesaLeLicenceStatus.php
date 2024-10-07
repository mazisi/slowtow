<?php

namespace App\Actions;

class WholesaLeLicenceStatus {

  static function getLicenceStatus($num_status) : string {
      $statusCodes = [
          '100' => 'Client Quoted',
          '200' => 'Deposit Invoiced',
          '300' => 'Deposit Paid',
          '400' => 'Prepare New Application',
          '500' => 'Application Submitted',
          '600' => 'Initial Application Fee',
          '700' => 'Application Lodged',
          '800' => 'Additional Documents Request',
          '900' => 'NLA 6 Proposed',
          '1000' => 'NLA 7 Submitted',
          '1100' => 'NLA 8 Issued',
          '1200' => 'Activation Fee',
          '1300' => 'NLA 9 Issued',
          '1400' => 'Original Licence',
          '1500' => 'Original Licence Delivered'
      ];

      return $statusCodes[$num_status] ?? '';
  }
}