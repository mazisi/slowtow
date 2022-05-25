<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Models\Nomination;
use App\Models\People;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        ]);
        if($nom){
            foreach($request->values as $val){
                $nom->persons()->attach($val);
            }
            return redirect(route('nominate',['slug' => $request->slug]))->with('success','Nominees created successfully');
        }
        return redirect(route('nominate',['slug' => $request->slug]))->with('error','Error creating nominees.');
    }
}
