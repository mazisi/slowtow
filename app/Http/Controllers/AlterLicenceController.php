<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Http\Request;
use App\Http\Requests\AlterationRequest;

class AlterLicenceController extends Controller
{

  public function index(Request $request){//return nominations page
    $licence = Licence::with('alterations')->whereSlug($request->slug)->first();
    return Inertia::render('Licences/Alteration',['licence'=> $licence]);
  }

    public function newAlteration(Request $request){
        $licence = Licence::with('alterations')->whereSlug($request->slug)->first();
        return Inertia::render('Licences/AlterLicence',['licence'=> $licence]);
    }

    public function store(AlterationRequest $request,$licence_id){
          $alter = Alteration::create([
            'licence_id' => $licence_id,
            'date' => $request->alteration_date,
            'status'  => last($request->status),
            'slug' => sha1(time()),
          ]);
          if($alter){
            return to_route('alterations',['slug' => $request->licence_slug])->with('success','Licence altered successfully.');
          }
          return to_route('alterations',['slug' => $request->licence_slug])->with('error','An error occured while altering licence.');
    }

    public function destroy($slug)
    {
       $alter = Alteration::whereSlug($slug)->first();
       if($alter->delete()){
        return back()->with('success','Alteration deleted successfully.');
      }
      return back()->with('error','An error occured while deleting alteration.');
    }
}
