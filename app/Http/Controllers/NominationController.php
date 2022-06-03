<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Licence;
use App\Models\Nomination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ValidatePeople;

class NominationController extends Controller
{
    /**
     * Get nominate page...
     * Return Nominate.vue where you select people & create nomination. 
     */
    public function index($slug){
        $licence = Licence::whereSlug($slug)->firstOrFail();
        $nominees = People::pluck('name','id');
        return Inertia::render('Nominations/Nominate',['licence' => $licence,'nominees' => $nominees]);
    }
    /**
     * Insert nomination.
     * Loop through selected people IDs & insert to database.
     */
    public function store(Request $request){
        $request->validate([
            "nomination_date" => "required|date",
            "people" => "required"
        ]);
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nom = Nomination::create([
            "date" => $request->nomination_date,
            "licence_id" => $licence->id,
            "status" => $request->status,
            "slug" => 'licence='.Str::replace('','_',$licence->trading_name).sha1(time())
        ]);
        if($nom){ 
            foreach($request->people as $val){//people are IDs from multiple select.;
                $nom->people()->attach($val);
            }
             return to_route('nominations',['slug' => $request->slug])->with('success','Nominees created successfully');
         }
             return to_route('nominations',['slug' => $request->slug])->with('error','Error creating nominees.');
    }
    /**
     * Get all nominations belonging to a certain licence.
     */
    public function nominations($slug){
        $nom = Licence::whereSlug($slug)->firstOrFail();
        $nominations = Nomination::with('licence','people')->where('licence_id',$nom->id)->get();
        return Inertia::render('Nominations/Nomination',['nominations' => $nominations]);
    }

    /**
     * Vue nominee.
     */
    public function viewIndividualNomination($slug){
        $person = People::with('nominations')->whereSlug($slug)->firstOrFail();
        return Inertia::render('Nominations/ViewIndividualNomination',['person' => $person]);
    }

    /**
     * Terminate individual.
     */
    public function terminate($id,$slug){
        $person = DB::table('nomination_people')
        ->whereId($id)
        ->update(['terminated_at' => now()]);

        if($person){
            return to_route('view-nomination',['slug' => $slug])->with('success','Person updated succesfully.');
        }
         return to_route('view-nomination',['slug' => $slug])->with('error','Error updating person.');
    }

    public function update(ValidatePeople $request){//update nominee
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
        if($person){//id is pivot id(nomination_people table)
            DB::table('nomination_people')->whereId($request->id)->update(['relationship' => $request->position]);
            Nomination::whereSlug($request->nomination_slug)->update(['status' => $request->status]);
           return to_route('view_nomination',['slug' => $request->slug])->with('success','Person updated succesfully.');
        }
        return to_route('view_nomination',['slug' => $request->slug])->with('error','Error updating person.');
    }
}
