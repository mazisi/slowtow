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
        $consultants = Consultant::with('companies')->get();
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
            $request->validate(['company*' => 'required|exists:companies,id']);
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

    public function show($slug)
    {
        $consultant = Consultant::with('companies')->whereSlug($slug)->first();
       return Inertia::render('Consultants/ViewConsultant',['consultant' => $consultant]);
    }
    
    public function unlinkConsultant($id)
    {
        dd($id);
    }
}
