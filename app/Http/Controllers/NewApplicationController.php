<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
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
        return Inertia::render('New Applications/Registration',['licence' => $licence]);
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
