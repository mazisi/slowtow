<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Licence;
use App\Models\Nomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class NominationController extends Controller
{
    public function index($slug){
        $licence = Licence::whereSlug($slug)->firstOrFail();
        $nominees = People::pluck('name','id');
        return Inertia::render('Nominations/Nominate',['licence' => $licence,'nominees' => $nominees]);
    }

    public function store(Request $request){
        $request->validate([
            "nomination_date" => "required|date",
            "values" => "required"
        ]);
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nom = Nomination::create([
            "date" => $request->nomination_date,
            "licence_id" => $licence->id,
            "slug" => 'licence='.$licence->trading_name.sha1(time())
        ]);
        if($nom){
            foreach($request->values as $val){
                $nom->persons()->attach($val);
            }
            return redirect(route('nominate',['slug' => $request->slug]))->with('success','Nominees created successfully');
        }
        return redirect(route('nominate',['slug' => $request->slug]))->with('error','Error creating nominees.');
    }

    public function nominations($slug){//all nominations belonging to licence
        $nom = Licence::whereSlug($slug)->firstOrFail();
        $nominations = Nomination::with('licence','people')->where('licence_id',$nom->id)->get();
        return Inertia::render('Nominations/Nomination',['nominations' => $nominations]);
    }

    /**
     * single person in nomination
     * Note that it returns ViewPerson page 
     */
    public function viewIndividualNomination($slug){
        $person = People::with('nominations')->whereSlug($slug)->firstOrFail();
        return Inertia::render('People/ViewPerson',['person' => $person,'isFromViewNominationPage'=> true]);
    }

    public function terminate($id,$slug){
        $person = DB::table('nomination_people')
        ->whereId($id)
        ->update(['terminated_at' => now()]);

        if($person){
            return Redirect::route('view-nomination',['slug' => $slug])->with('success','Person updated succesfully.');
         }
         return Redirect::route('view-nomination',['slug' => $slug])->with('error','Error updating person.');
    }
}
