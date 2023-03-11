<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceDocument;
use App\Models\LicenceType;
use App\Models\People;
use Illuminate\Http\Request;
use App\Models\Task;

class LicenceController extends Controller
{
    public function index(){
        $licences = Licence::with(["company","people","licence_type"])
//Search Only
            
        ->when(request('term') 
            && !request('licence_date') 
            && !request('licence_type') 
            && !request('active_status') 
            && !request('province'), 
            function ($query) {
            return $query
            ->where(function($query){

                $query->where(function($query){
                    $query->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                       ->orWhere('licence_number','LIKE','%'.request('term').'%');
                    });

                // $query->orWhereHas('company', function($query){
                //     $query->where('name', 'like', '%'.request('term').'%');                
                // });   
            });
            
        })
          
// search plus licence date    
        ->when(request('term') 
            && request('licence_date') 
            && !request('licence_type') 
            && !request('active_status') 
            && !request('province'), 
            function ($query){
                $query->whereMonth('licence_date',request('licence_date'))
                      ->where('trading_name','LIKE','%'.request('term').'%')
                      ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                      ->orWhere('licence_number','LIKE','%'.request('term').'%')
                      ->orWhereHas('company', function($query){
                         $query->where('name', 'like', '%'.request('term').'%');                
                     });   
            
            })

       // search and licence date and licence_type
            ->when(request('term') 
                && request('licence_date') 
                && request('licence_type') 
                && !request('active_status') 
                && !request('province'),
                function ($query){ 
                    $query->where('licence_type_id',request('licence_type'))
                    ->whereMonth('licence_date',request('licence_date'))
                    ->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                    // ->orWhereHas('company', function($query){
                    //     $query->where('name', 'like', '%'.request('term').'%');                
                    //  });               
                })

// search and licence date and licence_type and activeStatus == Active
            ->when(request('term') 
                && request('licence_date') 
                && request('licence_type') 
                && request('active_status') == 'Active'
                && !request('province'),
                function ($query){ 
                    $query->where('is_licence_active',true)
                          ->where('licence_type_id',request('licence_type'))
                          ->whereMonth('licence_date',request('licence_date'))
                          ->where('trading_name','LIKE','%'.request('term').'%')
                          ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                          ->orWhere('licence_number','LIKE','%'.request('term').'%');            
                })

// search and licence date and licence_type and activeStatus == Inactive
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Inactive'
                    && !request('province'),
                    function ($query){
                        $query->whereNull('is_licence_active')
                              ->orWhere('is_licence_active','0')
                              ->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'))
                              ->where('trading_name','LIKE','%'.request('term').'%')
                              ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                              ->orWhere('licence_number','LIKE','%'.request('term').'%');                 
                })

// search and province and licence date and licence_type and activeStatus == Active
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Active'
                    && request('province'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->whereNull('is_licence_active',true)
                              ->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'))
                              ->where('trading_name','LIKE','%'.request('term').'%')
                              ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                              ->orWhere('licence_number','LIKE','%'.request('term').'%');           
                })

// search and licence date and licence_type and activeStatus == Inactive plus province
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Inactive'
                    && request('province'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->whereNull('is_licence_active')
                              ->orWhere('is_licence_active','0')
                              ->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'))
                              ->where('trading_name','LIKE','%'.request('term').'%')
                              ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                              ->orWhere('licence_number','LIKE','%'.request('term').'%');                
                })
//Search and province only
                ->when(request('term') 
                    && request('province') 
                    && !request('licence_type') 
                    && !request('active_status')
                    && !request('licence_date'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->where('trading_name','LIKE','%'.request('term').'%')
                              ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                              ->orWhere('licence_number','LIKE','%'.request('term').'%');           
                })

//Search and licence_type only
                ->when(request('term') 
                    && request('licence_type') 
                    && !request('province') 
                    && !request('active_status')
                    && !request('licence_date'),
                    function ($query){ 
                        $query->where('licence_type_id',request('licence_type'))
                              ->where('trading_name','LIKE','%'.request('term').'%')
                              ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                              ->orWhere('licence_number','LIKE','%'.request('term').'%');            
                })
// search and activeStatus == Active 
                 ->when(request('term') 
                    && !request('licence_date') 
                    && !request('licence_type') 
                    && request('active_status') == 'Active'
                    && !request('province'),
                    function ($query){
                        $query->where('is_licence_active',true)
                            ->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');        
                })

 // search and activeStatus == Inactive 
                ->when(request('term') 
                    && !request('licence_date') 
                    && !request('licence_type') 
                    && request('active_status') == 'Inactive'
                    && !request('province'),
                    function ($query){ 
                        $query->whereNull('is_licence_active')
                              ->orWhere('is_licence_active','0')
                              ->where('trading_name','LIKE','%'.request('term').'%')
                              ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                              ->orWhere('licence_number','LIKE','%'.request('term').'%');         
                })
//Active and licence_type
                ->when(request('licence_type') 
                    && request('active_status') == 'Active',
                    function ($query){ 
                        $query->where('is_licence_active',true)
                              ->where('licence_type_id',request('licence_type'));         
                })
//Inactive and licence_type
                ->when( request('licence_type') 
                    && request('active_status') == 'Inactive',
                    function ($query){ 
                        $query->whereNull('is_licence_active')
                              ->orWhere('is_licence_active','0')
                              ->where('licence_type_id',request('licence_type'));         
                })
//Inactive and licence_date
                ->when(request('licence_date') 
                        && request('active_status') == 'Inactive',
                    function ($query){ 
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->whereNull('is_licence_active')
                              ->orWhere('is_licence_active','0');
                              
                })
//Active and licence_date
                ->when(request('licence_date') 
                    && request('active_status') == 'Active',
                    function ($query){ 
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('is_licence_active',true);                              
                })

//Active and province
                ->when( request('active_status') == 'Active'
                       && request('province'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->where('is_licence_active',true);                              
                })

//Inactive and province
                ->when(request('active_status') == 'Inactive'
                    && request('province'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->whereNull('is_licence_active')
                              ->orWhere('is_licence_active','0');
                              
                })

//Licence date and licence_type
                ->when(request('licence_date') 
                    && request('licence_type') ,
                    function ($query){
                        $query->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'));         
                })



        

                ->when(request('active_status') == 'Active' && request('licence_type'), 
                        function ($query){ 
                            return $query->where('is_licence_active',true)
                            ->where('licence_type_id',request('licence_type'))
                            ->where('is_licence_active',true);                
                })

                ->when(request('licence_type'), 
                    function ($query){ 
                        return $query->where('licence_type_id',request('licence_type'));                
                    })
                    
                    ->when(request('active_status') == 'Active' && request('licence_type'), 
                    function ($query){ 
                        return $query->where('is_licence_active',true)
                        ->where('licence_type_id',request('licence_type'))
                        ->where('is_licence_active',true);                
                })


            ->when(request('licence_date') && request('active_status') =='Inactive' && request('licence_type'), 
                function ($query){ 
                    return $query->whereMonth('licence_date',request('licence_date'))
                    ->where('licence_type_id',request('licence_type'))
                    ->where('is_licence_active',false)
                    ->whereNull('is_licence_active')
                    ->orWhere('is_licence_active','0');                
                })

            ->when(request('active_status') =='Active', 
                function ($query){ 
                    return $query->where('is_licence_active',true);                
                })
            
            ->when(request('province'), 
                function ($query){ 
                    return $query->where('province', 'LIKE','%'.request('province').'%');                
                })
            
            

            ->when(request('licence_date'), 
                function ($query){
                    return $query->whereMonth('licence_date',request('licence_date'));                
                })

                ->when(request('active_status') =='Inactive', 
                function ($query){ 
                    return $query->where('is_licence_active',false)
                    ->orWhereNull('is_licence_active')
                    ->orWhere('is_licence_active',0);                
                })
            ->latest()->paginate(20)->withQueryString();
            

       $all_licence_types = LicenceType::get();
        return Inertia::render('Licences/Licence',['licences' => $licences,'all_licence_types' => $all_licence_types]);
    }
    

    public function create(){
        $companies = Company::pluck('name','id');
        $people = People::pluck('full_name','id');
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('Licences/CreateLicence',['licence_dropdowns' => $licence_dropdowns,
        'companies' => $companies, 'people' => $people]);
    }


    public function updateActiveStatus(Request $request,$slug){
        $lic =Licence::whereSlug($slug)->first();
        if($request->unChecked){
          $lic->update(['is_licence_active' => NULL]);
        }else{
            $lic->update(['is_licence_active' => $request->status]);
        }
        return back()->with('success','Saved.');
    }

    public function store(Request $request){
       if($request->belongs_to === 'Company'){
          $request->validate(["company" => "required|exists:companies,id"]);
       }elseif($request->belongs_to === 'Person'){
           $request->validate([ "person" => "required|exists:people,id"]);
       }
       $request->validate([
        "trading_name" => "required",
        "licence_type" => "required",
        "province" => "required",
        "licence_number" => "required|unique:licences,licence_number"
    ]);
       
        Licence::create([
            "trading_name" => $request->trading_name,
            'belongs_to' => $request->belongs_to,
            "licence_type_id" => $request->licence_type,
            "licence_date" => $request->licence_date,
            "company_id"   => $request->company,
            "people_id"   => $request->person,
            "licence_number" => $request->licence_number,
            "old_licence_number" => $request->old_licence_number,
            "address" => $request->address,
            "address2" => $request->address2,
            "address3" => $request->address3,
            "province" => $request->province,
            "postal_code" => $request->postal_code,
            "is_licence_active" => $request->is_licence_active,
            'slug' => sha1(time()),
        ]);
        return redirect(route('licences'))->with('success','Licence created successfully.');
    }

    /**
     * Yes i colud have eager loaded licence with('documents')
     * but the way frontend is structured.
     * And also that its multiple
     */
    public function show(Request $request){
        $view = '';
        $licence = Licence::with('company','people','licence_documents')->whereSlug($request->slug)->first();
        $original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->latest()->first();
        $duplicate_original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Licence')->latest()->first();
        $original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence-Delivered')->latest()->first();
        $duplicate_original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Original-Licence-Delivered')->latest()->first();
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->get();

        if($licence->is_new_app){
            $view = 'ViewNewApp';
        }else{
            $view = 'ViewLicence';
        }
      
        return Inertia::render('Licences/'.$view,[
                                            'licence' => $licence,
                                            'licence_dropdowns' => $licence_dropdowns,
                                             'tasks' => $tasks,
                                             'companies' => $companies,
                                             'original_lic' => $original_lic,
                                             'duplicate_original_lic' => $duplicate_original_lic,
                                             'original_lic_delivered' => $original_lic_delivered,
                                             'duplicate_original_lic_delivered' => $duplicate_original_lic_delivered,
                                            ]);
    }

    public function update(Request $request,$slug){    
        if(empty($request->change_company)){
            $company_var = $request->company_id;
        }else{
            $request->validate(['change_company'=>'required|exists:companies,id']);
            $company_var = $request->change_company;
        }

        
        $update = Licence::whereSlug($slug)->update([
            "trading_name" => $request->trading_name,
            "licence_type_id" => $request->licence_type,
            "licence_date" => $request->licence_date,
            "licence_number" => $request->licence_number,
            "old_licence_number" => $request->old_licence_number,
            "address" => $request->address,
            "address2" => $request->address2,
            "address3" => $request->address3,
            "province" => $request->province,
            "postal_code" => $request->postal_code,
            "company_id" => $company_var
        ]);
        if($update){
            return back()->with('success','Licence updated successfully.');
        }
        return back()->with('error','Error updating licence.');
    }

    public function destroy($slug){
        $licence = Licence::whereSlug($slug)->first();
        if($licence->delete()){
           return to_route('licences')->with('success','Licences deleted successfully.');
        }
        return to_route('licences')->with('error','Error deleting licence.');
    }

    
}
