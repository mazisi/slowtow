<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;

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
        $client_invoiced = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Invoiced')->first();
        $renewal_issued = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Renewal Issued')->first();
        $client_quoted = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Quoted')->first();
        $renewal_doc = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Renewal Delivered')->first();
        $tasks = Task::where('model_id',$renewal->id)->where('model_type','Licence Renewal')->whereUserId(auth()->id())->get();
        return Inertia::render('Renewals/ViewLicenceRenewal',[
            'renewal' => $renewal,
            'tasks'  => $tasks,
            'client_invoiced' => $client_invoiced,
            'renewal_issued' => $renewal_issued,
            'client_quoted'  => $client_quoted,
            'renewal_doc' => $renewal_doc
        ]);
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

    public function uploadDocuments(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf",
            "doc_name" => "required|string|max:255",
            ]);
        
           $store_file = $request->document->store('renewalDocuments','public'); 
            $save_file = RenewalDocument::create([
                "licence_renewal_id" => $request->renewal_id,
                "document_name" => $request->doc_name,
                "document" => $store_file,
                "date" => $request->date,
                "doc_type" => $request->doc_type,
                'path'         => 'app/public/'
               ]);
       if($save_file){
            return back()->with('success','Document uploaded successfully.');
       }
       return back()->with('error','Error uploading document.');
    }

    public function deleteDocument($id){
        $model = RenewalDocument::find($id);
        if(!is_null($model->document)){
            unlink(public_path('storage/'.$model->document));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }
}