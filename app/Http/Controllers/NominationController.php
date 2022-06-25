<?php

namespace App\Http\Controllers;

use App\Models\Task;
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
     * For some reason,wc i dont know,search data(term) comes via Symphony\InputBag!!!!
     */
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nomination_years = Nomination::get(['slug', 'year']);
        return Inertia::render('Nominations/Nominate',['licence' => $licence,'nomination_years' => $nomination_years]);
    }
    /**
     * Insert nomination.
     * Loop through selected people IDs & insert to database.
     */
    public function store(Request $request){
        $request->validate([
            "year" => "required",
            "licence_id" => "required|exists:licences,id"
        ]);
        $exist = Nomination::where('licence_id',$request->licence_id)
                                    ->where('year',$request->year)->first();
        if (!is_null($exist)) {
           return back()->with('error', 'Sorry...Nomination for '.$request->year.' already exist.');
        }

        $nom = Nomination::create([
            "year" => $request->year,
            "licence_id" => $request->licence_id,
            "slug" => sha1(time())
        ]);
        if($nom){ 
           return back()->with('success','Nomination created successfully.');
         }
         return back()->with('error','Error creating nomination.');
    }
    /**
     * Get all nominations belonging to a certain licence.
     */
    public function nominations(Request $request){
        $nom = Licence::whereSlug($request->slug)->firstOrFail();
        $nominations = Nomination::with('licence','people')->where('licence_id',$nom->id)->get();
        return Inertia::render('Nominations/Nomination',['nominations' => $nominations]);
    }

    /**
     * Vue nominee.
     */
    public function viewIndividualNomination($slug){
        $nomination = Nomination::with('licence','people')->whereSlug($slug)->first();
        $nominees = People::pluck('full_name','id');
        $tasks = Task::where('model_type','Nomination')->where('model_id',$nomination->id)->whereUserId(auth()->id())->get();
        return Inertia::render('Nominations/ViewIndividualNomination',[
            'nomination' => $nomination,
            'nominees' => $nominees,
            'tasks' => $tasks]);
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

    public function update(ValidatePeople $request){dd('see me');
        $nom = Nomination::whereSlug($request->slug)->update([
            "date" => $request->nomination_date,
            "licence_id" => $request->licence_id,
            "status" => last($request->status),
        ]);
        if($nom){//id is pivot id(nomination_people table)
            DB::table('nomination_people')->whereId($request->id)->update(['relationship' => $request->position]);
            Nomination::whereSlug($request->nomination_slug)->update(['status' => $request->status]);
           return to_route('view_nomination',['slug' => $request->slug])->with('success','Person updated succesfully.');
        }
        return to_route('view_nomination',['slug' => $request->slug])->with('error','Error updating person.');
    }

    public function addSelectedNominees(Request $request){
        $nom = Nomination::find($request->nomination_id);
         foreach($request->selected_nominess as $selected){
            $exist = DB::table('nomination_people')
                         ->where('nomination_id',$nom->id)
                         ->where('people_id',$selected)
                         ->first();
            if(!is_null($exist)){
               continue;
            }
            $nom->people()->attach($selected);
         }
         return to_route('view_nomination',['slug' =>$nom->slug]);
    }

    public function detachNominee($nomination_id,$nominee_id){
        $nom = Nomination::find($nomination_id);
        $nom->people()->detach($nominee_id);
        return back();
    }
}
