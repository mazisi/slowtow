<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PeopleController extends Controller
{
    function index() {
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 

        $peopleCollection = DB::table('company_people')->whereIn('company_id',$get_company_ids)->get();
        //loop through the collection and get the people id
        $peopleIDS = [];
        foreach($peopleCollection as $person){
            $peopleIDS[] = $person->people_id;
        }
        $people = People::whereIn('id',$peopleIDS)->get();


        return Inertia::render('CompanyAdmin/People/MyPeople',[
            'people' => $people
        ]);
    }

    function show($id) {
       $person = People::with('people_documents')->whereId($id)->first();
       
        return Inertia::render('CompanyAdmin/People/ViewPerson',[
            'person' => $person
        ]);
    }
}
