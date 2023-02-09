<?php
namespace App\Exports;

use App\Models\LicenceRenewal;
use App\Models\LicenceRenewalExports;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class RenewalExport implements FromCollection, WithHeadings, ShouldAutoSize{

  use Exportable;
  
  // public function __construct(LicenceRenewal $renewal)
  //   {
  //       dd($this->renewal = $renewal);
  //   }

  public function headings():array{

    return[
      'ACTIVE/DEACTIVE',
      'TRADING NAME',
      'LICENCE NUMBER',
      'RENEWAL DATE',
      'RENEWAL AMOUNT',
      'QUOTED',
      'QUOTE SENT',
      'PAYMENT DATE',
      'INVOICE NUMBER',
      'PAYMENT TO LIQUOR BOARD',
      'RENEWAL GRANTED',
      'DELIVERY DATE ',
      'PROOF OF DELIVERY',
      'REASON / NOTES'
    ];
} 
    public function collection(){
        return LicenceRenewalExports::get([
        'is_active',
        'trading_name',
        'licence_number',
        'renewal_date',
        'renewal_amount',
        'is_quoted',
        'is_quote_sent',
        'payment_date',
        'invoice_number',
        'payment_to_liquour_board',
        'renewal_granted',
        'delivery_date',
        'proof_of_delivery',
        'notes'
    ]);
    }



    
}