<?php
namespace App\Exports;

use App\Models\LicenceRenewal;
use App\Models\LicenceRenewalExports;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RenewalExport implements FromCollection, WithHeadings{

  public function headings():array{
    return[
      'ACTIVE/DEACTIVE',
      'TRADING NAME',
      'LICENCE NUMBER',
      'RENEWAL DATE',
      'QUOTED',
      'QUOTE SENT',
      'PAYMENT DATE',
      'INVOICE NUMBER',
      'PAYMENT TO LIQUOR BOARD',
      'DELIVERY DATE ',
      'renewal_granted',
      'delivery_date',
      'proof_of_delivery',
      'notes'
    ];
} 
    public function collection(){
        return LicenceRenewalExports::all();
    }
}