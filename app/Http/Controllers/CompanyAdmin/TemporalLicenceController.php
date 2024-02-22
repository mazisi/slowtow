<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Consultant;
use App\Models\TemporalLicence;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemporalLicenceController extends Controller
{
    public function index(){
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 

        $licences = TemporalLicence::with('company','people')
        ->when(request('term'), 
            function ($query){ 
                return $query->whereHas('company', function($query){
                    $query->where('name', 'like', '%'.request('term').'%');
                })->orWhereHas('people', function($query){
                    $query->where('full_name', 'like', '%'.request('term').'%');                                      
                          })
                ->orWhere('liquor_licence_number','LIKE','%'.request('term').'%')
                ->orWhere('start_date','LIKE','%'.request('term').'%')
                ->orWhere('end_date','LIKE','%'.request('term').'%')
                ->orWhere('event_name','LIKE','%'.request('term').'%');
            
            })
        
               

            ->where('people_id',auth()->user()->people_id)
            ->orWhereIn('company_id',$get_company_ids)
            ->latest()->paginate(20)->withQueryString();

        return Inertia::render('CompanyAdmin/TemporalLicences/MyTemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = Consultant::pluck('first_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

   
        public function show($slug){
            $licence = TemporalLicence::with('company','people')->whereSlug($slug)->first();
    
            return Inertia::render('CompanyAdmin/TemporalLicences/ViewTemporalLicence',['licence' => $licence]);
     }

     public function processApplication(Request $request){
        $licence = TemporalLicence::with('company','people','temp_documents')->whereSlug($request->slug)->first();

        return Inertia::render('CompanyAdmin/TemporalLicences/ProcessApplication',
            ['licence' => $licence]);

     }

  
}
