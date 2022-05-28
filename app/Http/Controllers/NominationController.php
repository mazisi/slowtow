<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Licence;
use App\Models\Nomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            "values" => "required"
        ]);
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nom = Nomination::create([
            "date" => $request->nomination_date,
            "licence_id" => $licence->id,
            "status" => $request->status,
            "slug" => 'licence='.$licence->trading_name.sha1(time())
        ]);
        if($nom){ 
            foreach($request->values as $val){//values are IDs from multiple select.;
                $nom->persons()->attach($val);
            }
             return to_route('view-nomination',['slug' => $request->slug])->with('success','Nominees created successfully');
         }
             return to_route('view-nomination',['slug' => $request->slug])->with('error','Error creating nominees.');
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
        return Inertia::render('Nominations/ViewIndividualNomination',['person' => $person,'isFromViewNominationPage'=> true]);
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
}
