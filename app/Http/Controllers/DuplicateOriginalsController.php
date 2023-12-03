<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Nomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DuplicateOriginalsController extends Controller
{
    //
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nomination_years = Nomination::where('licence_id',$licence->id)->orderBy('year','DESC')->paginate(10)->withQueryString();
        $years = DB::table('years')->get()->pluck('year');//dropdown of years
        return Inertia::render('DuplicateOriginals/DuplicateOriginals',[
            'licence' => $licence,
            'nomination_years' => $nomination_years,
            'years' => $years
          ]);
       
    }
}
