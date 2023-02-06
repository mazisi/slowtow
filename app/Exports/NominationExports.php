<?php
namespace App\Exports;

use App\Models\TransferExport;
use App\Models\NominationExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NominationExports implements FromCollection, WithHeadings, ShouldAutoSize{

  public function headings():array{
    return[
      'TRADING NAME',
      'Client Name',
      'LICENCE NUMBER',
      'PROVINCE/REGION',
      'INVOICE NUMBER',
      'PAYMENT DATE',
      'DATE LODGED',
      'PROOF OF LODGEMENT',
      'DATE GRANTED',
      'CURRENT STATUS',
      'COMMENTS'
    ];
} 
    public function collection(){
        return NominationExport::get([
        'trading_name',
        'client_name',
        'licence_number',
        'province',
        'invoice_number',
        'payment_date',
        'date_logded',
        'proof_of_lodgement',
        'date_granted',
        'current_status',
        'notes'
    ]);
    }
    
}