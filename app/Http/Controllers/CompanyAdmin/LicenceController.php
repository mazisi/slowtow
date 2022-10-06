<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceDocument;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

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

            }elseif(!empty($request->licence_date) && empty($request->term) && empty($request->active_status)
            && empty($request->licence_type)){

                $licences = Licence::with(["company","people","licence_type"])
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
            
            $user = User::with('company','people')->whereId(auth()->id())->first();

            $companies = Company::whereHas('users', function($query) use($user){
                $query->where('user_id',$user->id);
            })->get();

            $get_company_ids = [];

            foreach($companies as $company){
                $get_company_ids[] = $company->id;
            } 
            $licences = Licence::with('licence_type','company','people')->whereIn('company_id',$get_company_ids)->get();    
        }



        $all_licence_types = LicenceType::get();

        return Inertia::render('CompanyAdminDash/Licences/Licence',['licences' => $licences,'all_licence_types' => $all_licence_types]);
    }

    /**
     * Yes i could have eager loaded licence with('documents')
     * but the way frontend is structured.
     */
    public function show($slug){
        $licence = Licence::with('company','people','licence_documents')->whereSlug($slug)->first();
        $original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->get();
        $duplicate_original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Licence')->get();
        $original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence-Delivered')->get();
        $duplicate_original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Original-Licence-Delivered')->get();
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->whereUserId(auth()->id())->get();

        return Inertia::render('CompanyAdminDash/Licences/ViewMyLicences',[
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



    
}
