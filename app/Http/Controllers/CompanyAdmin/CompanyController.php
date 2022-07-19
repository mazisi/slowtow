<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Http\Controllers\Controller;
use App\Models\User;

class CompanyController extends Controller
{
    
    public function index(){

   
        $user = User::with('company')->whereId(auth()->id())->first();
        return Inertia::render('CompanyAdminDash/MyCompanies',['user'=> $user]);
    }

    public function show($slug){
        $company = Company::with('users','licences','people')->whereSlug($slug)->first();
        $contrib_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','Contribution-Certificate')->get();
        $bee_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','BEE-Certificate')->get();
        $cipc_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','CIPC-Certificate')->get();
        $lta_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','LTA-Certificate')->get();
        $company_doc = CompanyDocument::where('company_id',$company->id)->where('document_type','Company-Document')->get();
        $tasks = Task::where('model_type','Company')->where('model_id',$company->id)->whereUserId(auth()->id())->get();
        $people = People::pluck('full_name','id');
        
        return Inertia::render('CompanyAdminDash/ViewCompany',[
            'company'=> $company,
            'people' => $people,
             'tasks' => $tasks,
             'contrib_cert' => $contrib_cert,
             'bee_cert' => $bee_cert,
             'cipc_cert' => $cipc_cert,
             'lta_cert' => $lta_cert,
             'company_doc' => $company_doc
            ]);
    }

   

}
