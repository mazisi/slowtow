<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Consultant;
use App\Models\TemporalLicence;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemporalLicenceController extends Controller
{
    public function index(Request $request){
        $licences = TemporalLicence::with('company','people')->where('people_id',auth()->user()->people_id)->get();
        return Inertia::render('CompanyAdminDash/Licences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = Consultant::pluck('first_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

   
    public function show($slug){
        $companies = Company::pluck('name','id');
        $people = Consultant::pluck('first_name','id');
        $licence = TemporalLicence::with('company','consultant')->whereSlug($slug)->first();
        return Inertia::render('TemporalLicences/ViewTemporalLicence',
        ['companies' => $companies,'licence' => $licence,'people' => $people]);
    }


  
}
