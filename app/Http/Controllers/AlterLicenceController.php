<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\AlterationDocument;
use App\Http\Requests\AlterationRequest;

class AlterLicenceController extends Controller
{

  public function index(Request $request){//return nominations page
    $licence = Licence::with('alterations')->whereSlug($request->slug)->first(['id','trading_name','slug']);
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
            'description' => $request->description,
            'slug' => sha1(time()),
          ]);
          if($alter){
            return to_route('view_alteration',['slug' => $alter->slug])->with('success','Licence altered successfully.');
          }
          return back()->with('error','An error occured while altering licence.');
    }


    public function show($slug){
      $alteration = Alteration::with('licence')->whereSlug($slug)->first();
      $client_invoiced = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Invoiced')->first();
      $alteration_letter = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alteration Letter')->first();
      $site_map_file = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','SiteMap File')->first();
      $tasks = Task::where('model_type','Alteration')->where('model_id',$alteration->id)->whereUserId(auth()->id())->get();
      
      return Inertia::render('Alterations/ViewAlteration',[
        'alteration' => $alteration,
        'client_invoiced' => $client_invoiced,
        'alteration_letter' => $alteration_letter,
        'site_map_file' => $site_map_file,
        'tasks' => $tasks
      ]);
    }


    public function update(Request $request){
      $request->validate([
        'alteration_date' => 'required|date'
      ]);
      $status = null;
      $alter = Alteration::whereSlug($request->slug)->first();
      if(!is_null($alter->status) && empty($request->status)){
        $db_status = $alter->status;
        $status = $db_status;
    }elseif(!empty($request->status)){
        $sorted_statuses = Arr::sort($request->status);
        $status = last($sorted_statuses);
    }
       $alter = Alteration::whereSlug($request->slug)->update([
        'status' => $status,
        'date' => $request->alteration_date,
        'description' => $request->description,
        'invoiced_at' => $request->invoiced_at
       ]);
       if($alter){
        return back()->with('success','Alteration updated succesfully.');
       }
       return back()->with('error','Error updating alteration.');

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
