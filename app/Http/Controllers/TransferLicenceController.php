<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;

class TransferLicenceController extends Controller
{
    public function index($slug){//NB..licence slug
        $licence = Licence::with('company')->whereSlug($slug)->first();
        $companies_dropdown = Company::where('id','!=',$licence->company_id)->pluck('name','id');//get companies list
        return Inertia::render('Licences/TransferLicence',
                                                ['licence' => $licence,
                                               'companies_dropdown' => $companies_dropdown
                                                ]);
    }

    public function store(Request $request,$slug){
       $request->validate([
           "new_company" => "required|exists:companies,id",
           "date" => "required|date",
           "old_company_id" => "required|exists:licences,company_id",
           "status" => "required",//check me and put enum validation
       ]);
       $transfer = LicenceTransfer::create([
        'company_id'=> $request->old_company_id,
        'new_company_id' => $request->new_company,
        'date' => $request->date,
        'status' => $request->status,
        'slug' => 'trans_from_'.$request->old_company.sha1(time())
       ]);

       if($transfer){
         Licence::where('company_id',$request->old_company_id)->update([
                                'company_id' => $transfer->new_company_id
         ]);
         return to_route('view_licence',['slug' => $slug])->with('success','Licence transfered successfully.');
       }
       return to_route('view_licence',['slug' => $slug])->with('errror','Oopps!!! An error occured while attempting licence transfer.');

      }
}
