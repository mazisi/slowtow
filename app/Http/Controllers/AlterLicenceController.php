<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlterationRequest;
use App\Models\Alteration;
use App\Models\Licence;
use Inertia\Inertia;

class AlterLicenceController extends Controller
{
    public function alterLicence($slug){
        $licence = Licence::with('alterations')->whereSlug($slug)->first();
        return Inertia::render('Licences/AlterLicence',['licence'=> $licence]);
    }

    public function store(AlterationRequest $request,$licence_id){
          $alter = Alteration::create([
            'licence_id' => $licence_id,
            'date' => $request->alter_date,
            'status'  => $request->alter_status,
            'slug' => sha1(time()),
          ]);
          if($alter){
            return back()->with('success','Licence altered successfully.');
          }
          return back()->with('error','An error occured while altering licence.');
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
