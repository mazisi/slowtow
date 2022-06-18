<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ConsultantController extends Controller
{
    public function index(){
        $queryString= request('q');
        $consultants = Consultant::with('companies')
                         ->when($queryString, function ($query, $queryString) {
                           $query->where('first_name','like','%'.$queryString.'%')
                         ->orWhere('last_name','like','%'.$queryString.'%')
                         ->orWhere('identity_number','like','%'.$queryString.'%');
                        })->orWhereHas('companies', function($query) use($queryString){
                            $query->where('name', 'like', '%'.$queryString.'%');
                        })->get();
        return Inertia::render('Consultants/Consultant',['consultants' => $consultants]);
    }

    public function create(){
        $companies = Company::pluck('name','id');
        return Inertia::render('Consultants/CreateConsultant',['companies' => $companies]);
    }

    public function store(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        if(!empty($request->company)){
            $request->validate(['company*' => 'exists:companies,id']);
        }        
        $consultant = Consultant::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'identity_number' => $request->identity_number,
            'slug' => $request->first_name.'_'.$request->last_name.'_'.sha1(time())
        ]);

        if($consultant && !empty($request->company)){
            $consultant->companies()->attach($request->company,
             ['position' => $request->position, 
             'percentage' => $request->percentage
            ]);
        return to_route('consultants')->with('success','Consultant created successfully.');            
        }
        return to_route('consultants')->with('error','Error..Something went wrong.');
    }

    public function show($slug){
        $consultant = Consultant::with('companies')->whereSlug($slug)->first();
        $companies = Company::pluck('name','id');
       return Inertia::render('Consultants/ViewConsultant',['consultant' => $consultant,'companies' => $companies]);
    }
    
    /**
     * Unlink consultant from company.
     */
    public function unlinkConsultant($id)
    {
       $unlink = DB::table('company_consultant')->where('id',$id)->delete();
       if($unlink){
        return back()->with('success','Consultant removed successfully.');            
        }
        return back()->with('error','Error..Something went wrong.');
    }

    public function update(Request $request,$slug){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        if(!empty($request->company)){
            $request->validate(['company*' => 'exists:companies,id']);
        }        
        $consultant = Consultant::whereSlug($slug)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'identity_number' => $request->identity_number,
            'slug' => $request->first_name.'_'.$request->last_name.'_'.sha1(time())
        ]);

        if($consultant){dd('check me');
            // DB::table('company_consultant')->where(id)->update();
            $consultant->companies()->attach($request->company,
             ['position' => $request->position, 
             'percentage' => $request->percentage
            ]);
        return to_route('consultants')->with('success','Consultant created successfully.');            
        }
        return to_route('consultants')->with('error','Error..Something went wrong.');
    }

}
