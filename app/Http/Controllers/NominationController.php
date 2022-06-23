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

        if($request->has('term')){
            $selected_nominees = People::whereIn('id', $request->input('term'))->get();
        }else{
            $selected_nominees = '';
        }
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nominees = People::pluck('full_name','id');
        return Inertia::render('Nominations/Nominate',['licence' => $licence,'nominees' => $nominees,
        'selected_nominees' => $selected_nominees]);
    }
    /**
     * Insert nomination.
     * Loop through selected people IDs & insert to database.
     */
    public function store(Request $request){
        $request->validate([
            "nomination_date" => "required|date",
            "people*" => "required",
            'status*' => 'required' 
        ]);
        $nom = Nomination::create([
            "date" => $request->nomination_date,
            "licence_id" => $request->licence_id,
            "status" => last($request->status),
            "slug" => sha1(time())
        ]);
        if($nom){ 
            foreach($request->people as $val){//people are IDs from multiple select.;
                $nom->people()->attach($val);
            }
             return to_route('nominations',['slug'=>$request->licence_slug])->with('success','Nominees created successfully');
         }
             return to_route('nominations',['slug'=>$request->licence_slug])->with('error','Error creating nominees.');
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
        $tasks = Task::where('model_type','Nomination')->where('model_id',$nomination->id)->whereUserId(auth()->id())->get();
        return Inertia::render('Nominations/ViewIndividualNomination',['nomination' => $nomination,'tasks' => $tasks]);
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
/**
 * Fetch people data on multi select.
 */
    //  public function fetchSelectedNominees(Request $request){
    //     if(count($request->people)){
    //         $selected_nominees = People::whereIn('id', $request->people)->get();
    //     }else{
    //         $selected_nominees = '';
    //     }
    //     $licence = Licence::whereSlug($request->slug)->firstOrFail();
    //     $nominees = People::pluck('full_name','id');
    //     return Inertia::render('Nominations/Nominate',['selected_nominees' => $selected_nominees,'licence' => 
    //                                                     $licence,'nominees' => $nominees,
    //                                                     'nominees' => $nominees]);
    // }
}
