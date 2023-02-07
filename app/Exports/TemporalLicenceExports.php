<?php
namespace App\Exports;

use App\Models\TransferExport;
use App\Models\TemporalLicenceExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemporalLicenceExports implements FromCollection, WithHeadings, ShouldAutoSize{

  public function headings():array{
    return[
      'EVENT NAME',
      'APPLICANT',
      'EVENT DATES',
      'INVOICE NUMBER',
      'PROVINCE/REGION',
      'PAYMENT DATE',
      'LICENCE NUMBER',
      'DATE LODGED',
      'PROOF OF LODGEMENT',
      'DATE GRANTED',
      'CURRENT STATUS',
      'COMMENTS'
    ];
} 
    public function collection(){
        return TemporalLicenceExport::get([
          'event_name',
          'applicant',
          'event_dates',
          'invoice_number',
          'region',
          'payment_date',
          'licence_number',
          'date_lodged',
          'proof_of_lodgement',
          'date_granted',
          'current_status',
          'notes',
    ]);
    }
    
}