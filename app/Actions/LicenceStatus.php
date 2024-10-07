<?php

namespace App\Actions;

class LicenceStatus {

  static function getLicenceStatus($num_status) : string {
      $statusCodes = [
          '100' => 'Client Quoted',
          '200' => 'Deposit Paid',
          '300' => 'Client Invoiced',
          '400' => 'Prepare New Application',
          '500' => 'Payment To The Liquor Board',
          '600' => 'Scanned Application',
          '700' => 'Application Lodged',
          '800' => 'Initial Inspection',
          '900' => 'Liquor Board Requests',
          '1000' => 'Final Inspection',
          '1100' => 'Activation Fee Requested',
          '1200' => 'Client Finalisation Invoice',
          '1300' => 'Client Paid',
          '1400' => 'Activation Fee Paid',
          '1500' => 'Licence Issued',
          '1600' => 'Licence Delivered'
      ];

      return $statusCodes[$num_status] ?? '';
  }
}