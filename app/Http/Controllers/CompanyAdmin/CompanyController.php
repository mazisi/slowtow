<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMailer;
use Illuminate\Support\Facades\Mail;

class CompanyController extends Controller
{
    
    public function index(){   
        $user = User::with('company')->whereId(auth()->id())->first();
        return Inertia::render('CompanyAdmin/MyCompanies',['user'=> $user]);
    }

    public function show($slug){
        $company = Company::with('users','licences','people', 'company_documents')->whereSlug($slug)->first();
        $linked_licences = Licence::where('company_id',$company->id)->paginate(10);
        $people = People::pluck('full_name','id');
        
        return Inertia::render('CompanyAdmin/Company/ViewMyCompany',[
            'company'=> $company,
            'people' => $people,
            'linked_licences' => $linked_licences
            ]);
    }


    public function update(Request $request){
       
        $company = Company::whereId($request->company_id)->first();

        if(($company->email != $request->email_address_1 
        || $company->email1 != $request->email_address_2 
        || $company->email2 != $request->email_address_3
        )
        
        || ($company->tel_number != $request->telephone_number_1 
        || $company->tel_number1 != $request->telephone_number_2)){
            Mail::to('mazisimsebele18@gmail.com')->send(new NotifyMailer($company, 'company'));
        }
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
            'website' => $request->website,
            'business_address' => $request->business_address,
            'business_address2' => $request->business_address2,
             'business_address3' => $request->business_address3,
            'business_province' => $request->business_province,
            'business_address_postal_code' => $request->business_address_postal_code,
            'postal_address' => $request->postal_address,
            'postal_code2' => $request->postal_address2,
            'postal_code3' => $request->postal_address3,
            'postal_province' => $request->postal_province,
            'postal_code' => $request->postal_code,
        ]);
        if($company){
            return back()->with('success','Company updated successfully.');
        }
        return back()->with('error','Error occured while updating company.');


    }
   

}
