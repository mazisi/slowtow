<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceDocument;
use App\Models\RenewalDocument;
use App\Models\DuplicateOriginal;
use App\Http\Controllers\Controller;
use App\Models\DuplicateOriginalDoc;

class LicenceController extends Controller
{
    public function index(){
        
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 


        $licences = Licence::with(["company","people","licence_type"])

        ->when(request('term'), function ($query) {
            return $query->where('licence_number','LIKE','%'.request('term').'%')
                          ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                          ->orWhere('trading_name','LIKE','%'.request('term').'%');

        })->when(request('term') && request('active_status') == 'Active', 
            function ($query){ 
                $query->whereNotNull('is_licence_active')
                ->orWhere('licence_number','LIKE','%'.request('term').'%')
                ->orWhere('old_licence_number','LIKE','%'.request('term').'%');
            
            })
            ->when(request('term') && request('licence_date') && request('licence_type'), 
                function ($query){ 
                    $query->whereMonth('licence_date',request('licence_date'))
                    ->orWhere('licence_type_id',request('licence_type'))
                    ->orWhere('licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%');            
                })

            ->when(request('licence_type'), 
                function ($query){ 
                    $query->where('licence_type_id',request('licence_type'));                
                })

            ->when(request('licence_date'), 
                function ($query){
                    return $query->whereMonth('licence_date',request('licence_date'));                
                })

            ->when(request('licence_date') && request('licence_type'), 
                function ($query){ 
                    $query->whereMonth('licence_date',request('licence_date'))
                    ->orWhere('licence_type_id',request('licence_type'));                
                })

            ->when(request('licence_date') && request('active_status') =='Inactive' && request('licence_type'), 
                function ($query){ 
                    $query->whereMonth('licence_date',request('licence_date'))
                    ->orWhere('licence_type_id',request('licence_type'))
                    ->whereNull('is_licence_active');                
                })

            ->when(request('term') && request('active_status') =='Active' && request('licence_type'), 
                function ($query){ 
                    $query->where('licence_type_id',request('licence_type'))
                        ->where('trading_name','LIKE','%'.request('term').'%')
                        ->orWhere('licence_number','LIKE','%'.request('term').'%')
                        ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('licence_type_id',request('licence_type'))
                    ->whereNotNull('is_licence_active');                
                })

            ->when(request('term') && request('active_status') =='Inactive', 
                function ($query){ 
                   $query->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->whereNull('is_licence_active')
                    ->orWhereHas('company', function($query){
                      $query->where('name', 'like', '%'.request('term').'%');                
                });
               })

            ->when(request('active_status') =='Active', 
                function ($query){ 
                    $query->whereNotNull('is_licence_active');                
                })

                ->when(request('province'), 
                function ($query){ 
                    $query->where('province', 'LIKE','%'.request('province').'%');                
                })

            ->when(request('province') && request('term'), 
                function ($query){ 
                    $query->where('province', 'LIKE','%'.request('province').'%')
                    ->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->orWhereHas('company', function($query){
                      $query->where('name', 'like', '%'.request('term').'%');                
                });
            }) 

            ->when(request('active_status') =='Inactive', 
                function ($query){ 
                    $query->whereNull('is_licence_active');                
                })

            ->whereIn('company_id',$get_company_ids)
            ->paginate(10);
        $all_licence_types = LicenceType::get();

        return Inertia::render('CompanyAdmin/Licences/Licence',['licences' => $licences,'all_licence_types' => $all_licence_types]);
    }

    
    public function show(Request $request, $slug)
    {
        $licence = Licence::with('company', 'people', 'licence_documents')
            ->whereSlug($slug)
            ->first();

        $duplicate_original_lic = LicenceDocument::where('licence_id', $licence->id)
        ->where('document_type', 'Duplicate-Licence')
        ->latest()
        ->first();


        $original_lic = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Original-Licence')
            ->latest()
            ->first();

        $licence_issued = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Licence Issued')
            ->latest()
            ->first();



        $original_lic_delivered = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Original-Licence-Delivered')
            ->latest()
            ->first();

        $licence_delivered = LicenceDocument::where('licence_id', $licence->id)
            ->where('document_type', 'Licence Delivered')
            ->latest()
            ->first();

            $fuck = DuplicateOriginal::where('licence_id', $licence->id)->first();

            $duplicate_original_lic_delivered = DuplicateOriginalDoc::where('duplicate_original_id', $fuck?->id)
            ->where('doc_type', 'Duplicate-Original-Licence-Delivered')
                    ->first();

            

        $companies = Company::pluck('name', 'id');
        $people = People::pluck('full_name', 'id');
        $licence_dropdowns = LicenceType::orderBy('licence_type')->get();


        $view = $licence->is_new_app ? 'ViewNewApp' : 'ViewMyLicences';

        return Inertia::render('CompanyAdmin/Licences/'. $view,[
            'licence' => $licence,
            'licence_dropdowns' => $licence_dropdowns,
            'companies' => $companies,
            'people' => $people,
            'original_lic' => $original_lic,
            'licence_issued' => $licence_issued,
            'duplicate_original_lic' => $duplicate_original_lic,
            'original_lic_delivered' => $original_lic_delivered,
            'licence_delivered' => $licence_delivered,
            'duplicate_original_lic_delivered' => $duplicate_original_lic_delivered
                                            ]);
    }


public function my_renewals(Request $request){
    $licence = Licence::with('licence_renewals')->whereSlug($request->slug)->first();
    $renewals = LicenceRenewal::where('licence_id',$licence->id)->paginate(10);
    return Inertia::render('CompanyAdmin/Renewals/MyRenewals',['licence' => $licence, 'renewals' => $renewals]);
}

public function view_my_renewal($slug){
    $renewal = LicenceRenewal::with('licence', 'renewal_documents')->whereSlug($slug)->first();

   
    return Inertia::render('CompanyAdmin/Renewals/ViewMyRenewal',
    ['renewal' => $renewal]);
}
    

public function registration()
{
    # code...
}
}
