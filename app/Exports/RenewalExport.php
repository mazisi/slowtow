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
      'PAYMENT TO LIQUOR BOARD',
      'DELIVERY DATE ',
      'PROOF OF DELIVERY',
      'REASON / NOTES'
    ];
} 
    public function collection(){
        return LicenceRenewalExports::get(['is_active',
        'trading_name',
        'licence_number',
        'renewal_date',
        'renewal_amount',
        'is_quoted',
        'is_quote_sent',
        'payment_date',
        'payment_to_liquour_board',
        'delivery_date',
        'proof_of_delivery',
        'notes'
    ]);
    }
    
}