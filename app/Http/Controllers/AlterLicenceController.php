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
    return Inertia::render('Alterations/Alteration',['licence'=> $licence]);
  }

    public function newAlteration(Request $request){
        $licence = Licence::with('alterations')->whereSlug($request->slug)->first();
        return Inertia::render('Alterations/AlterLicence',['licence'=> $licence]);
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


    public function show($slug){
      $alteration = Alteration::with('licence')->whereSlug($slug)->first();
      return Inertia::render('Alterations/ViewAlteration',['alteration' => $alteration]);
    }


    public function update(Request $request){
      $request->validate([
        'status*' => 'required|in:1,2,3,4',
        'alteration_date' => 'required|date'
      ]);
       $alter = Alteration::whereSlug($request->slug)->update([
        'status' => last($request->status),
        'date' => $request->alteration_date,
       ]);
       if($alter){
        return to_route('alterations',['slug' => $request->licence_slug])->with('success','Alteration updated succesfully.');
       }
       return to_route('alterations',['slug' => $request->licence_slug])->with('error','Error updating alteration.');

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
