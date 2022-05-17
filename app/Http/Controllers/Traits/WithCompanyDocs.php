<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Http\Requests\CompanyValidateRequest;

trait WithCompanyDocs{

  public function updateDetails(CompanyValidateRequest $request)
  {
    $company = Company::whereId($request->company_id)->update([
      'name' => $request->company_name,
      'company_type' => $request->company_type,
      'reg_number' => $request->reg_number,
      'vat_number' => $request->vat_number,
      'email' => $request->email_address_1,
      'email1' => $request->email_address_2,
      'email2' => $request->email_address_3,
      'tel_number' => $request->telephone_number_1,
      'tel_number1' => $request->telephone_number_2,
      'fax' => $request->fax_number,
      'website' => $request->website,
      'fax' => $request->fax_number,
      'address' => $request->business_address,
      'business_address_postal_code' => $request->business_address_postal_code,
      'postal_address' => $request->postal_address,
      'postal_address_code' => $request->postal_address_code,
  ]);
  return $company;

  }
    public function handleGatlaDocs(Request $request){
        if ($request->hasFile('gatla_certificate')) {
            
            foreach ($request->gatla_certificate as $file) {                
                $name = $file->getClientOriginalName();
                $file->store('company-docs','public');
                 CompanyDocument::create([
                    'gatla_certificate' => $name,
                    'gatla_date' => $request->gatla_date,
                    'gatla_valid' => $request->gatla_valid,
                    'company_id' => $request->company_id
                ]);                     
            }
        }
    }

    public function handleCipcNoticeOfIncorporationDocs(Request $request){
        if ($request->hasFile('cipc_notice_of_incorporation')) {
            
            foreach ($request->cipc_notice_of_incorporation as $file) {                
                $name = $file->getClientOriginalName();
                $file->store('company-docs','public');
                 CompanyDocument::create([
                    'cipc_notice_of_incorporation' => $name,
                    'company_id' => $request->company_id
                ]);                     
            }
        }
    }

    public function handleCipcMemorandumOfIncorporationDocs(Request $request){
      if ($request->hasFile('cipc_memorandum_of_incorporation')) {
          
          foreach ($request->cipc_memorandum_of_incorporation as $file) {                
              $name = $file->getClientOriginalName();
              $file->store('company-docs','public');
               CompanyDocument::create([
                  'cipc_notice_of_incorporation' => $name,
                  'company_id' => $request->company_id
              ]);                     
          }
      }
  }

  public function handleSarsDocs(Request $request){
    if ($request->hasFile('sars_certificate')) {
        
        foreach ($request->sars_certificate as $file) {                
            $name = $file->getClientOriginalName();
            $file->store('company-docs','public');
             CompanyDocument::create([
                'sars_certificate' => $name,
                'company_id' => $request->company_id
            ]);                     
        }
    }
}
}