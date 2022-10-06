<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use App\Models\People;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewApplicationController extends Controller
{
    public function create()
    {
        $persons = People::pluck('full_name','id');
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('New Applications/Index',[
            'persons' => $persons,
             'companies' => $companies,
             'licence_dropdowns' => $licence_dropdowns
        ]);
    }

    public function store(Request $request){
       $request->validate([
        'trading_name' => 'required',
        'licence_type' => 'required|exists:licence_types,id',
        'belongs_to' => 'required|in:Company,Person',
        'board_region' => 'required' 
       ]);

       if($request->belongs_to === 'Company'){
        $request->validate(['reg_number' => 'required', 'company' => 'required|exists:companies,id']);
       }else{
        $request->validate(['id_number' => 'required','person' => 'required|exists:people,id']);
       }

       $licence = Licence::create([
        'trading_name' => $request->trading_name,
        'licence_type_id' => $request->licence_type,
        'belongs_to' => $request->belongs_to,
        'company_id' => $request->company,
        'people_id' => $request->person,
        'board_region' => $request->board_region,
        'is_new_app' => true,
        'slug' => sha1(now())
       ]);
       if($licence){
        return 'ok';
       }
       return 'error';
    }
}
