<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    
    public function index(){   
        $user = User::with('company')->whereId(auth()->id())->first();
        return Inertia::render('CompanyAdmin/MyCompanies',['user'=> $user]);
    }

    public function show($slug){
        $company = Company::with('users','licences','people', 'company_documents')->whereSlug($slug)->first();
        $linked_licences = Licence::where('company_id',$company->id)->paginate(10);
        $people = People::pluck('full_name','id');
        
        return Inertia::render('CompanyAdmin/Company/ViewMyCompany',[
            'company'=> $company,
            'people' => $people,
            'linked_licences' => $linked_licences
            ]);
    }

   

}
