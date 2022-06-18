<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\TemporalLicence;
use Illuminate\Support\Facades\DB;

class LicenceRenewalController extends Controller
{
    public function renewLicence(Request $request){
        $licence = Licence::with('company')->whereSlug($request->slug)->first();
        $renewals = LicenceRenewal::where('licence_id',$licence->id)->get();
       return Inertia::render('Renewals/Renewals',['licence' => $licence,'renewals' => $renewals]);
    }


    public function store(Request $request){
       
        $request->validate([
            'renewal_date' => 'required',
        ]);

        $posted_date = strtotime($request->renewal_date);//Just force every date format to 'Y-m-d'
        $new_date = date('Y-m-d',$posted_date);
    
        $check_renewal = LicenceRenewal::where('licence_id',$request->licence_id)
                                        ->where('date',Str::substr($new_date, 0, 4))->first();
        if (!is_null($check_renewal)) {
           return back()->with('error', 'Licence already renewed for '.Str::substr($new_date, 0, 4));
        }

        // if($check_renewal->date >= Str::substr($new_date, 0, 4)){
        //     return back()->with('error', 'Licence already renewed for '.Str::substr($new_date, 0, 4));
        // }

        $renew = LicenceRenewal::create([
            'licence_id' => $request->licence_id,
            'date' => Str::substr($new_date, 0, 4),
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
        'renewal_date' => 'required',
        'renewal_id' => 'required'
       ]);
       $update = LicenceRenewal::whereId($request->renewal_id)->update([
        'date' => $request->renewal_date,
        'status' => last($request->status)
       ]);
       if($update){
        return back()->with('success','Renewal updated successfully.');
       }
       return back()->with('error','Sorry..An error occured.');

    }
}
