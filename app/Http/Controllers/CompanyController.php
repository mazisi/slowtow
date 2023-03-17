<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CompanyValidateRequest;
use App\Models\Licence;

class CompanyController extends Controller
{
    
    public function index(){
         try {
            if(checkNetworkStatus() != 1){
                return back()->with('error','Connection timeout');
             }
            $companies = Company::
              when(request('term') 
                && !request('active_status') 
                && !request('company_type'), 
                function ($query) {
                    $query->where('name','LIKE','%'.request('term').'%')
                    ->orWhere('reg_number','LIKE','%'.request('term').'%');  
                
                
            })
    
            //Search and company type
            ->when(request('term') 
                && !request('active_status') 
                && request('company_type'), 
                function ($query) {
                return $query
                ->where(function($query){
                    $query->where('name','LIKE','%'.request('term').'%')
                          ->where('company_type','LIKE','%'.request('company_type').'%');   
                  });
                
            })
    //Search and company type and Active
            ->when(request('term') 
                && request('active_status') == 'Active'
                && request('company_type'), 
                function ($query) {
                    $query->where('name','LIKE','%'.request('term').'%')
                          ->where('company_type', 'LIKE','%'.request('company_type').'%')
                          ->where('active','1');   
                             
            })
    
    //Search and company type and All
            ->when(request('term') 
                && request('active_status') == 'All'
                && request('company_type'), 
                function ($query) {
                    $query->where('name','LIKE','%'.request('term').'%')
                          ->where('company_type','LIKE','%'.request('company_type').'%');   
                 
                
            })
    
    //Search and Inactive
            ->when(request('term') 
                && request('active_status') == 'Inactive'
                && !request('company_type'), 
                function ($query) {
                    $query->where('name','LIKE','%'.request('term').'%')
                          ->where('active','0');   
                 
                
            })
    
    //Search and company type and Active
            ->when(request('term') 
                && request('active_status') == 'Active'
                && !request('company_type'), 
                function ($query) {
                    $query->where('name','LIKE','%'.request('term').'%')
                          ->where('active','1');   
                  
                
            })
    
    
    
    
            ->when(!request('term')  && !request('company_type') && request('active_status') == 'Inactive', 
                function ($query){ 
                    return $query->where('active','0');            
                })
    
                ->when(!request('term')  && !request('company_type') && request('active_status') == 'Active', 
                function ($query){ 
                    return $query->where('active','1');            
                })
                    
                    ->when(!request('term') && request('company_type') && request('active_status') == 'Inactive', 
                    function ($query){ 
                        $query->where('active',true)
                              ->where('company_type','LIKE','%'.request('company_type').'%');            
                    })
    
                    ->when(!request('term') && request('company_type') && request('active_status') == 'Active', 
                    function ($query){ 
                        $query->where('active',true)
                              ->where('company_type','LIKE','%'.request('company_type').'%');            
                    })
    
                ->when(!request('term')  && !request('active_status') && request('company_type'), 
                    function ($query){ 
                        $query->where('company_type','LIKE','%'.request('company_type').'%');                
                    })
                    ->latest()->paginate(20)->withQueryString();
    
            return Inertia::render('Company',['companies'=> $companies]);
         } catch (\Throwable $th) {
            return redirect('error');
         }
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
                'active' => $request->active,
                'slug' => sha1(time()),
            ]);

            if($company ){
                return redirect('/companies')->with('success', 'Company successfully created!');
            }
            return back()->with('error', 'Ooops!!Error creating company!');
        
    }


    public function show($slug){
        $company = Company::with('users','licences','people')->whereSlug($slug)->first();
        $linked_licences = Licence::where('company_id',$company->id)->paginate(10);
        $contrib_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','Contribution-Certificate')->latest()->first();
        $bee_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','BEE-Certificate')->latest()->first();
        $cipc_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','CIPC-Certificate')->latest()->first();
        $lta_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','LTA-Certificate')->latest()->first();
        $company_doc = CompanyDocument::where('company_id',$company->id)->where('document_type','Company-Document')->latest()->first();
        $tasks = Task::where('model_type','Company')->where('model_id',$company->id)->latest()->paginate(4)->withQueryString();
        $sars_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','SARS-Certificate')->latest()->first();
        $people = People::pluck('full_name','id');
        
        return Inertia::render('ViewCompany',[
            'company'=> $company,
            'people' => $people,
             'tasks' => $tasks,
             'contrib_cert' => $contrib_cert,
             'bee_cert' => $bee_cert,
             'cipc_cert' => $cipc_cert,
             'lta_cert' => $lta_cert,
             'company_doc' => $company_doc,
             'sars_cert' => $sars_cert,
             'linked_licences' => $linked_licences
            ]);
    }

    public function update(Request $request){
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
            return to_route('view_company',['slug'=> $company->slug])->with('success','Company updated successfully.');
        }
        return to_route('view_company',['slug'=> $company->slug])->with('error','Error occured while updating company.');


    }

    public function updateActiveStatus(Request $request,$slug){
        $comp =Company::whereSlug($slug)->first();
        if($request->unChecked){
          $comp->update(['active' => 0]);
        }else{
            $comp->update(['active' => $request->status]);
        }
        return back()->with('success','Saved.');
    }

    public function attachPeopleToCompany(Request $request,$company_id){
        $company = Company::find($company_id);
        foreach ($request->people as $person) {
            $exist = DB::table('company_people')
                         ->where('company_id',$company->id)
                         ->where('people_id',$person)
                         ->first();
            if(!is_null($exist)){
               continue;
            }
            $company->people()->attach($person);
        }
        return back()->with('success','People added successfully.');            
        
    }

   
public function updatePeople(Request $request,$pivot_id){
    try {
        $update = DB::table('company_people')
        ->whereId($pivot_id)
        ->update(['position' => $request->position]);
            if($update){
                return back()->with('success','Position updated successfully.'); 
            }

    } catch (\Throwable $th) {
        return back()->with('error','Error..Something went wrong.');
    }
}
  /**
     * Unlink person from company.
     */
    public function unlinkPerson($id){
       try {
        $unlink = DB::table('company_people')->where('id',$id)->delete();
       if($unlink){
        return back()->with('success','Person removed successfully.');            
        }
        
       } catch (\Throwable $th) {
        // throw $th;
        return back()->with('error','Error..Something went wrong.');
       }
    }

    /**
     * Delete company
     */
    public function destroy($slug){
        try {
            $company = Company::whereSlug($slug);
            $deleteLicences = Licence::where('company_id',$company->id)->get(['id']);
            foreach ($deleteLicences as $deleteLicence) {
                $deleteLicence->delete();
            }
            
            if($company->delete()){
                return to_route('companies')->with('success','Company deleted successfully.');            
            }
           
        } catch (\Throwable $th) {
            return to_route('companies')->with('error','Error..Something went wrong.');
        }
       
    }

}
