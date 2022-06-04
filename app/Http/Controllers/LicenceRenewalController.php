<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Models\LicenceRenewal;
use App\Models\TemporalLicence;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicenceRenewalController extends Controller
{
    public function renewLicence($slug){
        $licence = Licence::with('company')->whereSlug($slug)->first();
        $renewals = LicenceRenewal::where('licence_id',$licence->id)->get();
       return Inertia::render('Licences/RenewLicence',['licence' => $licence,'renewals' => $renewals]);
    }


    public function store(Request $request,$id,$slug){
        $request->validate([
            'renewal_date' => 'required|date',
            'renewal_status' => 'required'
        ]);
        $renew = LicenceRenewal::create([
            'licence_id' => $id,
            'date' => $request->renewal_date,
            'status' => $request->renewal_status,
            'slug' => sha1(time())
        ]);
        if($renew){
            return to_route('renew_licence',['slug' => $slug])->with('success','Licence renewed successfully.');
        }
        return to_route('renew_licence',['slug' => $slug])->with('error','An error occured while renewing licence.');
    }


    public function viewLicence($slug){
        $licence = TemporalLicence::whereSlug($slug)->first();
        return Inertia::render('Licences/ViewTemporalLicence',['licence' => $licence]);
    }

    public function destroy($slug)
    {
        $renewal = LicenceRenewal::whereSlug($slug)->first();
        if($renewal->delete()){
            return back()->with('success','Renewal deleted successful.');
        }
        return back()->with('error','Error deleting renewal.');
    }
}
