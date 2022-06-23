<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ValidatePeople;
use Illuminate\Support\Facades\Redirect;

class PersonController extends Controller
{
    public function index(Request $request){
        if($request->term && $request->withThrashed != '' && $request->active_status == 'Active'){


            // $people = People::where('full_name','LIKE','%'.$request->term.'%')
            //                 ->where('licence_status','1')
            //                 ->orWhere('licence_number','LIKE','%'.$request->term.'%')
            //                 ->orWhere('old_licence_number','LIKE','%'.$request->term.'%')
            //                 ->get();

        }elseif($request->term && $request->withThrashed != '' ){
           
               $people = People::with(["company"])->orWhere('full_name','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_1','LIKE','%'.$request->term.'%')
                            ->withTrashed()
                            ->get();
        
        }elseif($request->term && $request->active_status == 'Active'){
    
               $people = People::where('full_name', 'like', '%'.$request->term.'%')
                                ->orWhere('email_address_1','LIKE','%'.$request->term.'%')
                                ->orWhere('email_address_2','LIKE','%'.$request->term.'%')
                                ->whereActive('1')
                                ->get();
               
        }elseif($request->term){
            $people = People::where('full_name','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_1','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_2','LIKE','%'.$request->term.'%')
                            ->get();
        
        }elseif($request->term && $request->withThrashed != '' && $request->active_status == 'Inactive'){
                    $people = People::where('full_name','LIKE','%'.$request->term.'%')
                            ->where('active','!=','1')
                            ->orWhere('email_address_1','LIKE','%'.$request->term.'%')
                            ->orWhere('email_address_2','LIKE','%'.$request->term.'%')
                            ->get();

        }else{
            $people = People::whereNull('id')->get();
        }
        return Inertia::render('People/Person',['people' => $people]);
    }

    public function create(){
        return Inertia::render('People/CreatePerson');
    }

    public function store(ValidatePeople $request){
        
        $person = People::create([
        'full_name'=> $request->name.' '.$request->surname,
        'active'=> $request->active,
        'date_of_birth' => $request->date_of_birth,
        'id_number' => $request->id_number,
        'id_or_passport' => $request->id_or_passport,
        'identity_number' => $request->identity_number,
        'passport' => $request->passport_number,
        'email_address_1' => $request->email_address_1,
        'email_adddress_2' => $request->email_adddress_2,
        'cell_number' => $request->cell_number,
        'telephone' => $request->telephone,
        'position' => $request->position,
        'passport_valid_until' => $request->passport_valid_until,
        'valid_saps_clearance' => $request->valid_saps_clearance,
        'valid_certified_id' => $request->valid_certified_id,
        'saps_clearance_valid_until' => $request->saps_clearance_valid_until,
        'valid_fingerprint' => $request->valid_fingerprint,
        'valid_fingerprint_valid_until' => $request->valid_fingerprint_valid_until,
        'slug' => Str::replace(' ','_',$request->name).' '.Str::replace(' ','_',$request->surname).'_'.sha1(time()),
        ]);
        
        if($person){
           return Redirect::route('people')->with('success','Person created succesfully.');
        }
        return Redirect::route('people')->with('error','Error creating person.');
    }

    public function show($slug){
        $person = People::with('nominations')->whereSlug($slug)->first();
        $tasks = Task::where('model_type','Person')->where('model_id',$person->id)->whereUserId(auth()->id())->get();
        return Inertia::render('People/ViewPerson',['person' => $person,'tasks' => $tasks]);
    }

    public function update(ValidatePeople $request){
        $person = People::whereSlug($request->slug)->update([
        'full_name'=> $request->name,
        'active'=> $request->active,
        'date_of_birth' => $request->date_of_birth,
        'id_number' => $request->id_number,
        'id_or_passport' => $request->id_or_passport,
        'identity_number' => $request->identity_number,
        'passport' => $request->passport_number,
        'email_address_1' => $request->email_address_1,
        'email_address_2' => $request->email_adddress_2,
        'cell_number' => $request->cell_number,
        'position' => $request->position,
        'telephone' => $request->telephone,
        'passport_valid_until' => $request->passport_valid_until,
        'valid_saps_clearance' => $request->valid_saps_clearance,
        'valid_certified_id' => $request->valid_certified_id,
        'saps_clearance_valid_until' => $request->saps_clearance_valid_until,
        'valid_fingerprint' => $request->valid_fingerprint,
        'valid_fingerprint_valid_until' => $request->valid_fingerprint_valid_until,
        ]);
        if($person){
           return back()->with('success','Person updated succesfully.');
        }
        return back()->with('error','Error updating person.');
    }

    public function destroy($slug){
        $del = People::whereSlug($slug)->firstOrFail();
        if($del->delete()){
            return Redirect::route('people')->with('success','Person deleted succesfully.');
        }
        return Redirect::route('people')->with('error','Error updated person.');
    }

    
}
