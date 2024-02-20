<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Http\Controllers\Controller;
use App\Models\Company;

class LicenceTransferController extends Controller
{
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->first(['trading_name','slug']);
      
      $transfers = LicenceTransfer::with('old_person','new_person','old_company','new_company')
          ->whereHas('licence', function ($query) use($request) {
            $query->where('slug',$request->slug);
          })->paginate(10);

        return Inertia::render('CompanyAdmin/Transfers/MyTransfers',['licence' => $licence, 'transfers' => $transfers]);
    }

    public function view_my_transfer($slug) {
        $view_transfer = LicenceTransfer::with('old_person','new_person','old_company','new_company','licence','transfer_documents')->whereSlug($slug)->first();
        $get_current_company = Company::whereId($view_transfer->company_id)->first(['name']);
        
        return Inertia::render('CompanyAdmin/Transfers/ViewMyTransfer',
        ['view_transfer' => $view_transfer,
          'get_current_company' => $get_current_company 
        ]);
    }
}
