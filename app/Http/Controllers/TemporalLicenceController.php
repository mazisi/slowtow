<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Inertia\Inertia;
use App\Models\TemporalLicence;
use Illuminate\Http\Request;

class TemporalLicenceController extends Controller
{
    public function index(Request $request){
        if($request->term && $request->withThrashed != '' && $request->active_status == 'Active'){


            $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($request){
                                $query->where('name', 'like', '%'.$request->term.'%');
                            })->where('active','1')
                            ->orWhere('liquor_licence_number','LIKE','%'.$request->term.'%')
                            ->orWhere('start_date','LIKE','%'.$request->term.'%')
                            ->orWhere('end_date','LIKE','%'.$request->term.'%')
                            ->withTrashed()
                            ->get();

        }elseif($request->term && empty($request->withThrashed) ){
           
               $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($request){
                                $query->where('name', 'like', '%'.$request->term.'%');
                            })->orWhere('liquor_licence_number','LIKE','%'.$request->term.'%')
                            ->orWhere('start_date','LIKE','%'.$request->term.'%')
                            ->orWhere('end_date','LIKE','%'.$request->term.'%')
                            ->get();
        
        }elseif($request->term && $request->active_status == 'Active'){
    
               $licences = TemporalLicence::with(["company"])
               ->orWhereHas('company', function($query) use($request){
                   $query->where('name', 'like', '%'.$request->term.'%');
               })->orWhere('liquor_licence_number','LIKE','%'.$request->term.'%')
               ->orWhere('start_date','LIKE','%'.$request->term.'%')
               ->orWhere('end_date','LIKE','%'.$request->term.'%')
               ->where('active','1')
               ->get();
               
        }elseif($request->term){
            $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($request){
                                $query->where('name', 'like', '%'.$request->term.'%');
                            })->orWhere('liquor_licence_number','LIKE','%'.$request->term.'%')
                            ->orWhere('start_date','LIKE','%'.$request->term.'%')
                            ->orWhere('end_date','LIKE','%'.$request->term.'%')
                            ->get();
        
        }elseif($request->term && $request->withThrashed != '' && $request->active_status == 'Inactive'){
                    $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($request){
                                $query->where('name', 'like', '%'.$request->term.'%');
                            })
                            ->orWhere('trading_name','LIKE','%'.$request->term.'%')
                            ->where('active','!=','1')
                            ->withTrashed()
                            ->orWhere('liquor_licence_number','LIKE','%'.$request->term.'%')
                            ->orWhere('start_date','LIKE','%'.$request->term.'%')
                            ->orWhere('end_date','LIKE','%'.$request->term.'%')
                            ->get();

        }else{
            $licences = TemporalLicence::whereNull('id')->get();
        }
        
        return Inertia::render('TemporalLicences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies]);
    }

    public function store(Request $request){
       $request->validate([
           'company' => 'required|exists:companies,id',
           'liquor_licence_number' => 'required',
           'province' => 'required',
           'end_time' => 'required',
           'start_time' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date',
           ]);
           $temp = TemporalLicence::create([
            'company_id' => $request->company,
            'liquor_licence_number' => $request->liquor_licence_number,
            'province' => $request->province,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'start_time' => $request->start_time,
            'start_date' => $request->start_date,
            'municipality' => $request->municipality,
            'premises_description' => $request->premises_description,
            'extent_of_financial_honest'=> $request->extent_of_financial_honest,
            'slug' => sha1(time()),
            ]);
           if($temp){
              return to_route('temp_licences')->with('success','Temporal Licence issued successfully.');
           }
           return to_route('temp_licences')->with('error','AN unknown error occured while creating temporal Licence.');
    }

    public function show($slug){
        $companies = Company::pluck('name','id');
        $licence = TemporalLicence::with('company')->whereSlug($slug)->first();
        return Inertia::render('TemporalLicences/ViewTemporalLicence',['companies' => $companies,'licence' => $licence]);
    }


    public function update(Request $request,$slug){
        $request->validate([
            'company' => 'required|exists:companies,id',
            'liquor_licence_number' => 'required',
            'province' => 'required',
            'end_time' => 'required',
            'start_time' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            ]);
            $temp = TemporalLicence::whereSlug($slug)->update([
             'company_id' => $request->company,
             'liquor_licence_number' => $request->liquor_licence_number,
             'province' => $request->province,
             'end_date' => $request->end_date,
             'end_time' => $request->end_time,
             'start_time' => $request->start_time,
             'start_date' => $request->start_date,
             'municipality' => $request->municipality,
             'premises_description' => $request->premises_description,
             'extent_of_financial_honest'=> $request->extent_of_financial_honest
             ]);
            if($temp){
               return to_route('temp_licences')->with('success','Temporal Licence updated successfully.');
            }
            return to_route('temp_licences')->with('error','AN unknown error occured while updating temporal Licence.');
     }

     public function destroy($slug){
         $licence = TemporalLicence::whereSlug($slug);
         if($licence->delete()){
            return to_route('temp_licences')->with('success','Temporal Licence deleted successfully.');
         }
         return to_route('temp_licences')->with('error','AN unknown error occured while deleteing temporal Licence.');

     }
}
