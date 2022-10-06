<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceDocument;
use App\Models\LicenceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Task;

class LicenceController extends Controller
{
    public function index(Request $request){

        if(!empty($request->term) && $request->active_status == 'Active' && empty($request->licence_date) && empty($request->licence_type)){

               $licences = Licence::with(["company","people","licence_type"])
                 ->whereNotNull('is_licence_active')
                ->where(function($query) use($request){
                    $query->where('trading_name','LIKE','%'.$request->term.'%')
                    ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                    ->orWhere('old_licence_number','LIKE','%'.$request->term.'%')

                    ->orWhereHas('company', function($query) use($request){
                    $query->where('name', 'like', '%'.$request->term.'%');
                });
                })->get();

    }elseif(!empty($request->term) && $request->active_status == 'Active' 
      && !empty($request->licence_date) && !empty($request->licence_type)){

        $licences = Licence::with(["company","people","licence_type"])
                    ->where('licence_type_id',$request->licence_type)
                    ->whereMonth('licence_date',$request->licence_date)      
                    
                    ->where(function($query) use($request){
                        $query->where('trading_name','LIKE','%'.$request->term.'%')
                              ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                              ->orWhere('old_licence_number','LIKE','%'.$request->term.'%');
                        })->orWhereHas('company', function($query) use($request){
                            $query->where('name', 'like', '%'.$request->term.'%');
                           })->whereNotNull('is_licence_active')
                  ->get();

        }elseif(empty($request->term) && empty($request->active_status)
             && empty($request->licence_date) && !empty($request->licence_type)){

                $licences = Licence::with(["company","people","licence_type"])
                ->where('licence_type_id',$request->licence_type)                
                ->get();

            }elseif(empty($request->licence_date) && empty($request->term) && empty($request->active_status)
            && empty($request->licence_type) && !empty($request->province)){

                $licences = Licence::with(["company","people","licence_type"])
                ->where('province',$request->province)                
                ->get();

            }elseif(!empty($request->licence_date) && empty($request->term) && empty($request->active_status)
            && empty($request->licence_type) && empty($request->province)){

                $licences = Licence::with(["company","people","licence_type"])
                    ->whereMonth('licence_date',$request->licence_date)->get();

            }elseif(!empty($request->licence_date) && empty($request->term) && $request->active_status == 'Active'
            && !empty($request->licence_type) && !empty($request->province)){
                $licences = Licence::with(["company","people","licence_type"])
                    ->where('licence_type_id',$request->licence_type)
                    ->where('is_licence_active',1)
                    ->where('province',$request->province)   
                    ->whereMonth('licence_date',$request->licence_date)->get();

            }elseif(!empty($request->licence_date) && empty($request->term) && empty($request->active_status)
            && !empty($request->licence_type)){

                $licences = Licence::with(["company","people","licence_type"])
                    ->where('licence_type_id',$request->licence_type)
                    ->whereMonth('licence_date',$request->licence_date)->get();


                }elseif(!empty($request->licence_date) && $request->active_status =='Inactive'
                && !empty($request->licence_type) && empty($request->term) ){
                    
                    $licences = Licence::with(["company","people","licence_type"])
                      ->where(function($query) use($request){
                        $query->where('licence_type_id',$request->licence_type)
                         ->whereMonth('licence_date',$request->licence_date);
                        })->whereNull('is_licence_active')->get();

        }elseif(empty($request->licence_date) && $request->active_status =='Active'
        && !empty($request->licence_type) && !empty($request->term) ){
                        

                    $licences = Licence::with(["company","people","licence_type"])
                      ->where(function($query) use($request){
                        $query->where('licence_type_id',$request->licence_type)
                         ->whereNotNull('is_licence_active');
                        })
                        ->where(function($query) use($request){
                            $query->where('licence_type_id',$request->licence_type)
                            ->where('trading_name','LIKE','%'.$request->term.'%')
                        ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                        ->orWhere('old_licence_number','LIKE','%'.$request->term.'%');
                            })
                        
                        ->orWhereHas('company', function($query) use($request){
                            $query->where('name', 'like', '%'.$request->term.'%');
                        })->get();

        }elseif($request->active_status == 'All' && empty($request->term)){

          $licences = Licence::with(["company","people","licence_type"])->get();

        }elseif(!empty($request->term) && empty($request->active_status)){

            $licences = Licence::with(["company","people","licence_type"])
                            ->orWhereHas('company', function($query) use($request){
                                $query->where('name', 'like', '%'.$request->term.'%');
                            })->orWhere('trading_name','LIKE','%'.$request->term.'%')
                            ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                            ->orWhere('old_licence_number','LIKE','%'.$request->term.'%')
                            ->get();
        
        }elseif(!empty($request->term)  && $request->active_status == 'Inactive'){

                    $licences = Licence::with(["company","people","licence_type"])
                         ->whereNull('is_licence_active')
                        ->where(function($query) use($request){
                    $query->where('trading_name','LIKE','%'.$request->term.'%')
                    ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                    ->orWhere('old_licence_number','LIKE','%'.$request->term.'%')

                    ->orWhereHas('company', function($query) use($request){
                    $query->where('name', 'like', '%'.$request->term.'%');
                });
                })->get();

    }elseif(empty($request->term)  && $request->active_status == 'Active'){
        $licences = Licence::with(["company","people","licence_type"])
                         ->whereNotNull('is_licence_active')->get();

    }elseif(empty($request->term)  && $request->active_status == 'Inactive'){
        $licences = Licence::with(["company","people","licence_type"])
                            ->whereNull('is_licence_active')->get();

    }elseif(!empty($request->term)  && $request->active_status == 'All'){

                    $licences = Licence::with(["company","people","licence_type"])
                        ->where(function($query) use($request){
                    $query->where('trading_name','LIKE','%'.$request->term.'%')
                    ->orWhere('licence_number','LIKE','%'.$request->term.'%')
                    ->orWhere('old_licence_number','LIKE','%'.$request->term.'%')

                    ->orWhereHas('company', function($query) use($request){
                    $query->where('name', 'LIKE', '%'.$request->term.'%');
                });
                })->get();

        }else{
            $licences = Licence::with('company','people','licence_type')->whereNull('id')->get();
        }
        $all_licence_types = LicenceType::get();
        return Inertia::render('Licences/Licence',['licences' => $licences,'all_licence_types' => $all_licence_types]);
    }

    public function create(){
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('Licences/CreateLicence',['licence_dropdowns' => $licence_dropdowns,
        'companies' => $companies]);
    }

    public function store(Request $request){
       
        $request->validate([
            "trading_name" => "required",
            "licence_type" => "required",
            "company" => "required|exists:companies,id",
            "province" => "required",
            "licence_number" => "required"
        ]);
        Licence::create([
            "trading_name" => $request->trading_name,
            "licence_type_id" => $request->licence_type,
            "licence_date" => $request->licence_date,
            "company_id"   => $request->company,
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
        $licence = Licence::with('company','people','licence_documents')->whereSlug($request->slug)->first();
        $original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->get();
        $duplicate_original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Licence')->get();
        $original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence-Delivered')->get();
        $duplicate_original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Original-Licence-Delivered')->get();
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->whereUserId(auth()->id())->get();

        return Inertia::render('Licences/ViewLicence',[
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
            "is_licence_active" => $request->is_licence_active,
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
