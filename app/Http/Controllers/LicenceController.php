<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LicenceController extends Controller
{
    public function index(Request $request){

        if($request->term && $request->withThrashed != '' && $request->active_status == 'Active'){

            $licences = Licence::with('company')->when($request->term,function($query,$term){
             $query->where('trading_name','LIKE','%'.$term.'%')
                   ->where('licence_status','1')
                   ->withTrashed();
            })->get();
        }elseif($request->term && $request->withThrashed != '' ){
            $licences = Licence::with('company')->when($request->term,function($query,$term){
                $query->where('trading_name','LIKE','%'.$term.'%')
                      ->withTrashed();
               })->get();
        
        }elseif($request->term && $request->active_status == 'Active'){
    
            $licences = Licence::with('company')->when($request->term,function($query,$term){
                $query->where('trading_name','LIKE','%'.$term.'%')
                      ->where('active','1');
               })->get();
        }elseif($request->term){
            $licences = Licence::with('company')->when($request->term,function($query,$term){
                $query->where('trading_name','LIKE','%'.$term.'%');
               })->get();
        
        }elseif($request->term && $request->withThrashed != '' && $request->active_status == 'Inactive'){
            $licences = Licence::with('company')->when($request->term,function($query,$term){
                $query->where('trading_name','LIKE','%'.$term.'%')
                      ->where('licence_status','!=','1')
                      ->withTrashed();
         })->get();

        }else{
            $licences = Licence::with('company')->whereNull('trading_name')->get();
        }
        return Inertia::render('Licence',['licences' => $licences]);
    }

    public function create($slug){
        $company = Company::whereSlug($slug)->firstOrFail();
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('CreateLicence',['licence_dropdowns' => $licence_dropdowns,
        'company' => $company]);
    }

    public function store(Request $request,$slug){
       
        $request->validate([
            "trading_name" => "required",
            "licence_type" => "required",
            "licence_date" => "required",
        ]);
        $get_comp = Company::whereSlug($slug)->first();
        Licence::create([
            "trading_name" => $request->trading_name,
            "licence_type" => $request->licence_type,
            "licence_date" => $request->licence_date,
            "company_id"   => $get_comp->id,
            "licence_number" => $request->licence_number,
            "old_licence_number" => $request->old_licence_number,
            "licence_expire_date" => $request->licence_expire_date,
            "file_number" => $request->file_number,
            "account_number" => $request->account_number,
            "address" => $request->address,
            "province" => $request->province,
            "consultant_name" => $request->consultant_name,
            "must_renew" => $request->must_renew,
            "licence_status" => $request->is_licence_active,
            'slug' => Str::replace(' ','_',$request->trading_name).sha1(time()),
        ]);
        return redirect(route('licences'))->with('success','Licence created successfully.');
    }

    public function show($slug){
        $licence = Licence::with('company','licence_documents')->whereSlug($slug)->firstOrFail();
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('ViewLicence',['licence' => $licence,'licence_dropdowns' => $licence_dropdowns]);
    }

    public function update(Request $request,$slug){
        $update = Licence::whereSlug($slug)->update([
            "trading_name" => $request->trading_name,
            "licence_type" => $request->licence_type,
            "licence_date" => $request->licence_date,
            "licence_number" => $request->licence_number,
            "old_licence_number" => $request->old_licence_number,
            "licence_expire_date" => $request->licence_expire_date,
            "file_number" => $request->file_number,
            "account_number" => $request->account_number,
            "address" => $request->address,
            "province" => $request->province,
            "consultant_name" => $request->consultant_name,
            "must_renew" => $request->must_renew,
            "licence_status" => $request->is_licence_active,
        ]);
        if($update){
            return redirect(route('view_licence',['slug' => $slug]))->with('success','Licence updated successfully.');
        }
        return redirect(route('view_licence',['slug' => $slug]))->with('error','Error updating licence.');
    }
}
