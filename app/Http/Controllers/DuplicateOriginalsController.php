<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Licence;
use App\Models\Alteration;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LiquorBoardRequest;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Console\View\Components\Task;

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
    public function view_duplicate(){
       
      $duplicate_original = Alteration::with('licence')->find(14);
return Inertia::render('DuplicateOriginals/ViewDuplicate',[
        'duplicate_original' => $duplicate_original
    ]);
    }
}
