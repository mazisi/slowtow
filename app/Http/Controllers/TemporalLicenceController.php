<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Consultant;
use App\Models\People;
use Inertia\Inertia;
use App\Models\TemporalLicence;
use Illuminate\Http\Request;

class TemporalLicenceController extends Controller
{
    public function index(Request $request){
        $queryString = request('term');
        if(!empty($queryString) && $request->withThrashed != '' && $request->active_status == 'Active'){

          
            $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                            })->orWhereHas('consultant', function($query) use($queryString){
                                $query->where('first_name', 'like', '%'.$queryString.'%')
                                      ->where('last_name', 'like', '%'.$queryString.'%');
                                      })->where('active','1')
                            ->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->withTrashed()
                            ->get();

        }elseif(!empty($queryString) && !empty($request->withThrashed) ){
            
               $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                            })->orWhereHas('consultant', function($query) use($queryString){
                                $query->where('first_name', 'like', '%'.$queryString.'%')
                                      ->where('last_name', 'like', '%'.$queryString.'%');
                                      })->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->get();
        
        }elseif(!empty($queryString) && $request->active_status == 'Active'){
    
               $licences = TemporalLicence::with(['company','consultant'])
               ->orWhereHas('company', function($query) use($request){
                   $query->where('name', 'like', '%'.$queryString.'%');
               })->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
               ->orWhereHas('consultant', function($query) use($queryString){
                $query->where('first_name', 'like', '%'.$queryString.'%')
                      ->where('last_name', 'like', '%'.$queryString.'%');
                      })->orWhere('start_date','LIKE','%'.$queryString.'%')
                    ->orWhere('end_date','LIKE','%'.$queryString.'%')
                    ->where('active','1')
                    ->get();
               
        }elseif(!empty($queryString)){
            
            $licences = TemporalLicence::with('company','consultant')
                            ->where('liquor_licence_number','like','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                            })->orWhereHas('consultant', function($query) use($queryString){
                                $query->where('first_name', 'like', '%'.$queryString.'%')
                                      ->where('last_name', 'like', '%'.$queryString.'%');
                            })->get();
        
        }elseif(!empty($queryString) && $request->withThrashed != '' && $request->active_status == 'Inactive'){
                    $licences = TemporalLicence::with(["company"])
                            ->orWhereHas('company', function($query) use($queryString){
                                $query->where('name', 'like', '%'.$queryString.'%');
                                
                            })->orWhereHas('consultant', function($query) use($queryString){
                                $query->where('first_name', 'like', '%'.$queryString.'%')
                                      ->where('last_name', 'like', '%'.$queryString.'%');
                            })
                            ->orWhere('trading_name','LIKE','%'.$queryString.'%')
                            ->where('active','!=','1')
                            ->withTrashed()
                            ->orWhere('liquor_licence_number','LIKE','%'.$queryString.'%')
                            ->orWhere('start_date','LIKE','%'.$queryString.'%')
                            ->orWhere('end_date','LIKE','%'.$queryString.'%')
                            ->get();

        }else{
            $licences = TemporalLicence::whereNull('id')->get();
        }
        
        return Inertia::render('TemporalLicences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = Consultant::pluck('first_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

    public function store(Request $request){
       $request->validate([
           'liquor_licence_number' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date',
           'belongs_to' => 'required|in:Person,Company'
           ]);
           if(is_null($request->consultant)){
            $request->validate(['company' => 'required']);
            $temp = TemporalLicence::create([
                'company_id' => $request->company,
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'belongs_to' => $request->belongs_to,
                'slug' => sha1(time()),
                ]);

           }elseif($request->belongs_to == 'Person'){
            $request->validate(['consultant' => 'required']);
            $temp = TemporalLicence::create([
                'consultant_id' => $request->consultant,
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'belongs_to' => $request->belongs_to,
                'slug' => sha1(time()),
                ]);

           }
           
           if($temp){
              return to_route('temp_licences')->with('success','Temporal Licence issued successfully.');
           }
           return to_route('temp_licences')->with('error','AN unknown error occured while creating temporal Licence.');
    }

    public function show($slug){
        $companies = Company::pluck('name','id');
        $people = Consultant::pluck('first_name','id');
        $licence = TemporalLicence::with('company','consultant')->whereSlug($slug)->first();
        return Inertia::render('TemporalLicences/ViewTemporalLicence',
        ['companies' => $companies,'licence' => $licence,'people' => $people]);
    }


    public function update(Request $request,$slug){
        $request->validate([
            'liquor_licence_number' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'belongs_to' => 'required|in:Person,Company'
            ]);

            if(is_null($request->consultant)){
                $request->validate(['company' => 'required']);
                $temp = TemporalLicence::whereSlug($slug)->update([
                    'company_id' => $request->company,
                    'liquor_licence_number' => $request->liquor_licence_number,
                    'end_date' => $request->end_date,
                    'start_date' => $request->start_date,
                    'belongs_to' => $request->belongs_to
                    ]);
    
               }elseif($request->belongs_to == 'Person'){
                $request->validate(['consultant' => 'required']);
                $temp = TemporalLicence::whereSlug($slug)->update([
                    'consultant_id' => $request->consultant,
                    'liquor_licence_number' => $request->liquor_licence_number,
                    'end_date' => $request->end_date,
                    'start_date' => $request->start_date,
                    'belongs_to' => $request->belongs_to
                    ]);
    
               }
    
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
