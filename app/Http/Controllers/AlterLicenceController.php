<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Http\Request;
use App\Models\AlterationDate;
use App\Http\Requests\AlterationRequest;

class AlterLicenceController extends Controller
{

  public function index(Request $request){
    $licence = Licence::whereSlug($request->slug)->first(['id','trading_name','slug']);
    $alterations = Alteration::where('licence_id',$licence->id)->latest()->paginate(10)->withQueryString();
    return Inertia::render('Alterations/Alteration',[
      'licence'=> $licence,
      'alterations' => $alterations]);
  }

    public function newAlteration(Request $request){
        $licence = Licence::with('alterations')->whereSlug($request->slug)->first(['id','trading_name','slug']);
        return Inertia::render('Alterations/AlterLicence',['licence'=> $licence]);
    }

    public function store(AlterationRequest $request,$licence_id){
          $alter = Alteration::create([
            'licence_id' => $licence_id,
            'logded_at'    => $request->alteration_date,
            'status'  => last($request->status),
            'slug' => sha1(time()),
          ]);
          if($alter){
            return to_route('view_alteration',['slug' => $alter->slug])->with('success','Licence altered successfully.');
          }
          return back()->with('error','An error occured while altering licence.');
    }


    public function show($slug){
      $alteration = Alteration::with('licence','documents','dates')->whereSlug($slug)->first();      
      $tasks = Task::where('model_type','Alteration')->where('model_id',$alteration->id)->latest()->paginate(4)->withQueryString();     
      return Inertia::render('Alterations/ViewAlteration',[
        'alteration' => $alteration,
        'tasks' => $tasks
      ]);
    }


    public function update(Request $request){
      try {
      $status = null;
      if ($request->status) {
        if ($request->unChecked) {
            $status = $request->prevStage;
        } else {
            $status = $request->status[0];
        }
    }
       Alteration::whereSlug($request->slug)->update([
        'status' => $status <= 0 ? NULL : $status,
       ]);
      
        return back()->with('success','Alteration stage succesfully.');
       
      } catch (\Throwable $th) {
        return back()->with('error','Error updating alteration.');
      }
    }


    public function updateAlterationDate(Request $request, $slug){
      $alter = AlterationDate::create([
            'dated_at' => $request->dated_at,
            'alteration_id' => $request->licence_id,
            'stage' => $request->stage,
        ]);
        if($request->stage == 'Alterations Lodged'){
          $alter->update(['logded_at' => $request->dated_at]);
        }
        return back()->with('success','Date updated succesfully.');
        
    }

    public function destroy($slug, $licence_slug)
    {
       $alter = Alteration::whereSlug($slug)->first();
       if($alter->delete()){
        return to_route('alterations', ['slug' => $licence_slug])->with('success','Alteration deleted successfully.');
      }
      return to_route('alterations', ['slug' => $licence_slug])->with('error','An error occured while deleting alteration.');
    }
}
