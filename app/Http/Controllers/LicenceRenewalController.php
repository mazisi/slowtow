<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;

class LicenceRenewalController extends Controller
{
    public function renewLicence(Request $request){
        $licence = Licence::with('company')->whereSlug($request->slug)->first();
        $renewals = LicenceRenewal::where('licence_id',$licence->id)->get();
       return Inertia::render('Renewals/Renewals',['licence' => $licence,'renewals' => $renewals]);
    }


    public function store(Request $request){
       
        $request->validate([
            'year' => 'required',
        ]);

        $check_renewal = LicenceRenewal::where('licence_id',$request->licence_id)
                                        ->where('date',$request->year)->first();
        if (!is_null($check_renewal)) {
           return back()->with('error', 'Licence already renewed for '.$request->year);
        }

        $renew = LicenceRenewal::create([
            'licence_id' => $request->licence_id,
            'date' => $request->year,
            'slug' => sha1(time())
        ]);
        if($renew){
         return back()->with('success','Licence renewed successfully.');
        }
        return back()->with('error','An error occured while renewing licence.');
    }


    public function show($slug){
        $renewal = LicenceRenewal::with('licence')->whereSlug($slug)->first();
        return Inertia::render('Renewals/ViewLicenceRenewal',['renewal' => $renewal]);
    }

    public function destroy($slug)
    {
        $renewal = LicenceRenewal::whereSlug($slug)->first();
        if($renewal->delete()){
            return back()->with('success','Renewal deleted successful.');
        }
        return back()->with('error','Error deleting renewal.');
    }

    public function update(Request $request){
       $request->validate([
        'year' => 'required',
        'renewal_id' => 'required'
       ]);

       $ren = LicenceRenewal::find($request->renewal_id);
        if(!is_null($ren->status) && empty($request->status)){
            $db_status = $ren->status;
            $status = $db_status;
        }elseif(!empty($request->status)){
            $sorted_statuses = Arr::sort($request->status);
            $status = last($sorted_statuses);
        }

       $ren->update([
        'date' => $request->year,
        'status' => $status
       ]);
       if($ren){
        return back()->with('success','Renewal updated successfully.');
       }
       return back()->with('error','Sorry..An error occured.');

    }
}
