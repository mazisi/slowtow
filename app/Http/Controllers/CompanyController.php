<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Http\Requests\CompanyValidateRequest;
use App\Models\Task;

class CompanyController extends Controller
{
    
    public function index(Request $request){

      if($request->term && $request->withThrashed != '' && $request->active_status == 'Active'){

        $companies = Company::when($request->term,function($query,$term){
         $query->where('name','LIKE','%'.$term.'%')
               ->where('active','1')
               ->withTrashed();
        })->get();
    }elseif($request->term && $request->withThrashed != '' ){
        $companies = Company::when($request->term,function($query,$term){
            $query->where('name','LIKE','%'.$term.'%')
                  ->withTrashed();
           })->get();
    
    }elseif($request->term && $request->active_status == 'Active'){

        $companies = Company::when($request->term,function($query,$term){
            $query->where('name','LIKE','%'.$term.'%')
                  ->where('active','1');
           })->get();
    }elseif($request->term){  
        $companies = Company::when($request->term,function($query,$term){
            $query->where('name','LIKE','%'.$term.'%');
           })->get();      
    }else{
        $companies = Company::whereNull('name')->get();
    }
        return Inertia::render('Company',['companies'=> $companies]);
    }

    public function create() {
        return Inertia::render('CreateNewCompany');
    }

    public function store(CompanyValidateRequest $request)
    {       
            $company = Company::create([
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
                'address' => $request->business_address,
                'business_address_postal_code' => $request->business_address_postal_code,
                'postal_address' => $request->postal_address,
                'postal_address_code' => $request->postal_address_code,
                'active' => $request->active,
                'slug' => Str::replace(' ','_',$request->company_name).sha1(time()),
            ]);

            if($company ){
                return redirect('/companies')->with('success', 'Company successfully created!');
            }
            return back()->with('error', 'Ooops!!Error creating company!');
        
    }


    public function show($slug)
    {
        $company = Company::with('users')->whereSlug($slug)->first();
        $tasks = Task::where('model_type','Company')->where('model_id',$company->id)->whereUserId(auth()->id())->get();
        return Inertia::render('ViewCompany',['company'=> $company, 'tasks' => $tasks]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        $company = Company::whereId($request->company_id)->first();
        $company->update([
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
            'active' => $request->active,
        ]);
        if($company){
            return to_route('view_company',['slug'=> $company->slug])->with('success','Company updated successfully.');
        }
        return to_route('view_company',['slug'=> $company->slug])->with('error','Error occured while updating company.');

        if ($request->hasFile('gatla_certificate')) {   
                    // $name = $request->gatla_certificate->getClientOriginalName();
                    $name = $request->gatla_certificate->store('company-docs', 'public');
                    CompanyDocument::create([
                        'gatla_certificate' => $name,
                        'gatla_date' => $request->gatla_date,
                        'gatla_valid' => $request->gatla_valid,
                        'company_id' => $request->company_id
                    ]);     
               
        }

        if ($request->hasFile('cipc_notice_of_incorporation')) {
              
                $name= $request->cipc_notice_of_incorporation->store('company-docs','public');
                 CompanyDocument::create([
                    'cipc_notice_of_incorporation' => $name,
                    'company_id' => $request->company_id
                ]);                     
            }
        
        if ($request->hasFile('cipc_memorandum_of_incorporation')) {
                            
                $name =$request->cipc_memorandum_of_incorporation->store('company-docs','public');
                 CompanyDocument::create([
                    'cipc_notice_of_incorporation' => $name,
                    'company_id' => $request->company_id
                ]);                     
            }
        
       

        if ($request->hasFile('sars_certificate')) {
                $name = $request->sars_certificate->store('company-docs','public');
                 CompanyDocument::create([
                    'sars_certificate' => $name,
                    'company_id' => $request->company_id
                ]);                     
            
        }

    }

    public function destroy($id)
    {
        //
    }
   

}
