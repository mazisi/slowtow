<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceDocument;
use App\Models\LicenceType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class NewApplicationController extends Controller
{
    public function create()
    {
        $persons = People::pluck('full_name','id');
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('New Applications/Index',[
            'persons' => $persons,
             'companies' => $companies,
             'licence_dropdowns' => $licence_dropdowns
        ]);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'trading_name' => 'required',
                'licence_type' => 'required|exists:licence_types,id',
                'belongs_to' => 'required|in:Company,Person',
                'board_region' => 'required' 
               ]);
        
               if($request->belongs_to === 'Company'){
                $request->validate(['reg_number' => 'required', 'company' => 'required|exists:companies,id']);
               }else{
                $request->validate(['id_number' => 'required','person' => 'required|exists:people,id']);
               }
        
               $licence = Licence::create([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->company,
                'people_id' => $request->person,
                'board_region' => $request->board_region,
                'is_new_app' => true,
                'board_region' => $request->board_region,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'is_licence_active' => 1,
                'slug' => sha1(now())
               ]);
               if($licence){
                return to_route('view_licence',['slug' => $licence->slug])->with('success','Licence created successfully.');
               }
               
        } catch (\Throwable $th) {
            return back()->with('error','An error occured.Please try again.');
        }
       
    }

    public function update(Request $request,$slug){
        try {
            $request->validate([
                'trading_name' => 'required'
               ]);             
        
               $licence = Licence::whereSlug($slug)->update([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->company_id,
                'board_region' => $request->board_region,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'licence_number' => $request->licence_number,
                'latest_renewal' => $request->latest_renewal,
                'licence_date' => $request->licence_date,
                'id_number' => $request->id_number,
                'reg_number' => $request->reg_number
               ]);
               if($licence){
                return back()->with('success','Licence updated successfully.');
               }
               
        } catch (\Throwable $th) {
            throw $th;
            //return back()->with('error','An error occured.Please try again.');
        }
       
    }

    public function view_registration(Request $request)
    {
        $licence = Licence::with('company')->whereSlug($request->slug)->first();
        $gba_application_form = LicenceDocument::where('document_type','GLB Application Forms')->where('licence_id',$licence->id)->first(['id','document_name']);
        $client_invoiced = LicenceDocument::where('document_type','Client Invoiced')->where('licence_id',$licence->id)->first(['id','document_name']);
        $application_forms = LicenceDocument::where('document_type','Application Forms')->where('licence_id',$licence->id)->first(['id','document_name']);
        $company_docs = LicenceDocument::where('document_type','Company Documents')->where('licence_id',$licence->id)->first(['id','document_name']);
        $cipc_docs = LicenceDocument::where('document_type','CIPC Documents')->where('licence_id',$licence->id)->first(['id','document_name']);
        $id_docs = LicenceDocument::where('document_type','ID Documents')->where('licence_id',$licence->id)->first(['id','document_name']);
        $police_clearance = LicenceDocument::where('document_type','Police Clearance')->where('licence_id',$licence->id)->first(['id','document_name']); 
        $tax_clearance = LicenceDocument::where('document_type','Tax Clearance')->where('licence_id',$licence->id)->first(['id','document_name']); 
        $lta_certificate = LicenceDocument::where('document_type','LTA Certificate')->where('licence_id',$licence->id)->first(['id','document_name']); 
        $shareholding_info = LicenceDocument::where('document_type','Shareholding Info')->where('licence_id',$licence->id)->first(['id','document_name']);
        $financial_interests = LicenceDocument::where('document_type','Financial Interests')->where('licence_id',$licence->id)->first(['id','document_name']);
        $_500m_affidavict  = LicenceDocument::where('document_type','500m Affidavit')->where('licence_id',$licence->id)->first(['id','document_name']);
        $adverts  = LicenceDocument::where('document_type','Adverts')->where('licence_id',$licence->id)->first(['id','document_name']);
        $zoning_affidavict  = LicenceDocument::where('document_type','Zoning Affidavit')->where('licence_id',$licence->id)->first(['id','document_name']);
        $proof_of_occupation  = LicenceDocument::where('document_type','Proof of Occupation')->where('licence_id',$licence->id)->first(['id','document_name']);
        $menu  = LicenceDocument::where('document_type','Menu')->where('licence_id',$licence->id)->first(['id','document_name']);
        $representations  = LicenceDocument::where('document_type','Representations')->where('licence_id',$licence->id)->first(['id','document_name']);
        $photographs  = LicenceDocument::where('document_type','Photographs')->where('licence_id',$licence->id)->first(['id','document_name']);
        $consent_letter  = LicenceDocument::where('document_type','Municipal Consent Ltr')->where('licence_id',$licence->id)->first(['id','document_name']);
        $zoning_certificate  = LicenceDocument::where('document_type','Zoning Certificate')->where('licence_id',$licence->id)->first(['id','document_name']);
        $mapbook_plans  = LicenceDocument::where('document_type','Mapbook Plans')->where('licence_id',$licence->id)->first(['id','document_name']);
        $google_map_plans  = LicenceDocument::where('document_type','Google Map Plans')->where('licence_id',$licence->id)->first(['id','document_name']);
        $description  = LicenceDocument::where('document_type','Description')->where('licence_id',$licence->id)->first(['id','document_name']);
        $site_plans  = LicenceDocument::where('document_type','Site Plans')->where('licence_id',$licence->id)->first(['id','document_name']);
        $advert_photographs  = LicenceDocument::where('document_type','Advert Photographs')->where('licence_id',$licence->id)->first(['id','document_name']);
        $newspaper_adverts  = LicenceDocument::where('document_type','Newspaper Adverts')->where('licence_id',$licence->id)->first(['id','document_name']);
        $payment_to_liqour_board  = LicenceDocument::where('document_type','Payment To The Liquor Board')->where('licence_id',$licence->id)->first(['id','document_name']);
        
        
        
        return Inertia::render('New Applications/Registration',[
            'licence' => $licence,
             'gba_application_form' => $gba_application_form,
             'client_invoiced' => $client_invoiced,
            'application_forms' => $application_forms,
            'company_docs' => $company_docs,
            'cipc_docs' => $cipc_docs,
             'id_docs' => $id_docs,
            'police_clearance' => $police_clearance,
            'tax_clearance' => $tax_clearance,
            'lta_certificate' => $lta_certificate,
            'shareholding_info' => $shareholding_info,
            'financial_interests' => $financial_interests,
            '_500m_affidavict' => $_500m_affidavict,
            'adverts' => $adverts,
            'zoning_affidavict' => $zoning_affidavict,
            'proof_of_occupation' => $proof_of_occupation,
            'representations' => $representations,
            'photographs' => $photographs,
            'consent_letter' => $consent_letter,
            'zoning_certificate' => $zoning_certificate,
            'menu' => $menu,
            'mapbook_plans' => $mapbook_plans,
            'google_map_plans' => $google_map_plans,
            'description' => $description,
            'site_plans' => $site_plans,
            'advert_photographs' => $advert_photographs,
            'newspaper_adverts' => $newspaper_adverts,
            'payment_to_liqour_board' => $payment_to_liqour_board]);
    }

    public function updateRegistration(Request $request, $slug){
       try {
        $licence = Licence::whereSlug($slug)->first();
        $status = '';
        if(!is_null($licence->status) && empty($request->status)){
            $db_status = $licence->status;
            $status = $db_status;
        }elseif(!empty($request->status)){
            $sorted_statuses = Arr::sort($request->status);
            $status = last($sorted_statuses);
        }

        $licence->update([
            'deposit_paid_at' => $request->deposit_paid_at,
            'liquor_board_at' => $request->liquor_board_at,
            'application_lodged_at' => $request->application_lodged_at,
            'initial_inspection_at' => $request->initial_inspection_at,
            'final_inspection_at' => $request->final_inspection_at,
            'activation_fee_requested_at' =>$request->activation_fee_requested_at,
            'client_paid_at' => $request->client_paid_at,
            'activation_fee_paid_at' => $request->activation_fee_paid_at,
            'licence_issued_at' => $request->licence_issued_at,
            'licence_delivered_at' => $request->licence_delivered_at,
            'status' => $status,
           ]);
           return back()->with('success','Updated successfully');
       } catch (\Throwable $th) {
         return back()->with('success','An error occured while updating.');
       }
       
    }
}
