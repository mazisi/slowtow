<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 
        
        $user = User::with('company.people')->whereId(auth()->id())->first();

        $licences = Licence::whereIn('company_id',$get_company_ids)
                            ->orWhere('people_id',auth()->id())->count();

        $temp_licences = TemporalLicence::whereIn('company_id',$get_company_ids)
                                        ->orWhere('people_id',auth()->id())->count();

        // $people = User::where('company_id',auth()->user()->company->id)->count();
        $peopleCollection = DB::table('company_people')->whereIn('company_id',$get_company_ids)->get();
        //loop through the collection and get the people id
        $peopleIDS = [];
        foreach($peopleCollection as $person){
            $peopleIDS[] = $person->people_id;
        }
        $people = People::whereIn('id',$peopleIDS)->count();
        
        return Inertia::render('CompanyAdmin/Dashboard',
        [
            'licences' => $licences,
            'user' => $user,
            'people' => $people,
            'companies' => count($get_company_ids),
            'temp_licences' => $temp_licences
        ]);
    }
}
