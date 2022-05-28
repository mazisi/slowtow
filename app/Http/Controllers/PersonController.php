<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ValidatePeople;
use Illuminate\Support\Facades\Redirect;

class PersonController extends Controller
{
    public function index(){
        $people = People::get();
        return Inertia::render('People/Person',['people' => $people]);
    }

    public function create(){
        return Inertia::render('People/CreatePerson');
    }

    public function store(ValidatePeople $request){
        
        $person = People::create([
        'name'=> $request->name,
        'active'=> $request->active,
        'initials'=> $request->initials,
        'surname' => $request->surname,
        'date_of_birth' => $request->date_of_birth,
        'id_number' => $request->id_number,
        'id_or_passport' => $request->id_or_passport,
        'identity_number' => $request->identity_number,
        'passport' => $request->passport_number,
        'email_address_1' => $request->email_address_1,
        'email_adddress_2' => $request->email_adddress_2,
        'cell_number' => $request->cell_number,
        'fax_number' => $request->fax_number,
        'telephone' => $request->telephone,
        'home_address' => $request->home_address,
        'home_address_postal_code' => $request->home_address_postal_code,
        'postal_code' => $request->postal_code,
        'work_address' => $request->work_address,
        'work_address_postal_code' => $request->work_address_postal_code,
        'passport_valid_until' => $request->passport_valid_until,
        'valid_saps_clearance' => $request->valid_saps_clearance,
        'valid_certified_id' => $request->valid_certified_id,
        'saps_clearance_valid_until' => $request->saps_clearance_valid_until,
        'valid_fingerprint' => $request->valid_fingerprint,
        'valid_fingerprint_valid_until' => $request->valid_fingerprint_valid_until,
        'slug' => Str::replace(' ','_',$request->name).'_'.sha1(time()),
        ]);
        
        if($person){
           return Redirect::route('people')->with('success','Person created succesfully.');
        }
        return Redirect::route('people')->with('error','Error creating person.');
    }

    public function show($slug){
        $person = People::with('nominations')->whereSlug($slug)->first();
        return Inertia::render('People/ViewPerson',['person' => $person]);
    }

    public function update(ValidatePeople $request){
        $person = People::whereSlug($request->slug)->update([
        'name'=> $request->name,
        'active'=> $request->active,
        'initials'=> $request->initials,
        'surname' => $request->surname,
        'date_of_birth' => $request->date_of_birth,
        'id_number' => $request->id_number,
        'id_or_passport' => $request->id_or_passport,
        'identity_number' => $request->identity_number,
        'passport' => $request->passport_number,
        'email_address_1' => $request->email_address_1,
        'email_adddress_2' => $request->email_adddress_2,
        'cell_number' => $request->cell_number,
        'fax_number' => $request->fax_number,
        'telephone' => $request->telephone,
        'home_address' => $request->home_address,
        'home_address_postal_code' => $request->home_address_postal_code,
        'postal_code' => $request->postal_code,
        'work_address' => $request->work_address,
        'work_address_postal_code' => $request->work_address_postal_code,
        'passport_valid_until' => $request->passport_valid_until,
        'valid_saps_clearance' => $request->valid_saps_clearance,
        'valid_certified_id' => $request->valid_certified_id,
        'saps_clearance_valid_until' => $request->saps_clearance_valid_until,
        'valid_fingerprint' => $request->valid_fingerprint,
        'valid_fingerprint_valid_until' => $request->valid_fingerprint_valid_until,
        ]);
        if($person){
           return Redirect::route('people')->with('success','Person updated succesfully.');
        }
        return Redirect::route('people')->with('error','Error updating person.');
    }

    public function destroy($slug){
        $del = People::whereSlug($slug)->firstOrFail();
        if($del->delete()){
            return Redirect::route('people')->with('success','Person deleted succesfully.');
        }
        return Redirect::route('people')->with('error','Error updated person.');
    }

    
}
