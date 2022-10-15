<?php
namespace App\Exports;

use App\Models\TransferExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransferExports implements FromCollection, WithHeadings{

  public function headings():array{
    return[
      'CURRENT TRADING NAME',
      'GAU/GLB No',
      'PROVINCE/REGION',
      'DEPOSIT INVOICE',
      'DEPOSIT PAID',
      'DATE LODGED',
      'PROOF OF LODGEMENT',
      'FINALISATION INVOICE',
      'FINALISATION PAYMENT',
      'DATE GRANTED',
      'CURRENT STATUS',
      'COMMENTS'
    ];
} 
    public function collection(){
        return TransferExport::get([
        'trading_name',
        'gau_or_blg_number',
        'province',
        'deposit_invoice',
        'deposit_paid',
        'date_logded',
        'proof_of_lodgement',
        'finalisation_invoice',
        'finalisation_payment',
        'date_granted',
        'current_status',
        'notes'
    ]);
    }
    
}