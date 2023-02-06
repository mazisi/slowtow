<?php
namespace App\Exports;

use App\Models\ExistingLicenceExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExistingLicenceExports implements FromCollection, WithHeadings, ShouldAutoSize{

  public function headings():array{
    return[
      'TRADING NAME',
      'LICENCE TYPE',
      'LICENCE NUMBER',
      'PROVINCE/REGION',
      'DEPOSIT INVOICE',
      'DEPOSIT PAID',
      'DATE LODGED',
      'PROOF OF LODGEMENT',
      'ACTIVATION FEE PAID',
      'FINAL INVOICE',
      'FULL PAYMENT',
      'DATE GRANTED',
      'CURRENT STATUS',
      'FOCUS FOR THE WEEK',
      'COMMENTS'
    ];
} 
    public function collection(){
        return ExistingLicenceExport::get([
        'trading_name',
        'licence_type',
        'licence_number',
        'province',
        'deposit_invoice',
        'deposit_paid',
        'date_logded',
        'proof_of_lodgement',
        'activation_fee_paid',
        'final_invoice',
        'full_payment',
        'date_granted',
        'current_status',
        'focus_for_the_week',
        'notes'
    ]);
    }
    
}