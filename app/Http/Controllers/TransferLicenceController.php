<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;

class TransferLicenceController extends Controller
{
    public function index(Request $request){//NB..licence slug
        $licence = Licence::with('company')->whereSlug($request->slug)->first();
        $companies_dropdown = Company::where('id','!=',$licence->company_id)->pluck('name','id');//get companies list
        return Inertia::render('Licences/TransferLicence',
                                                ['licence' => $licence,
                                               'companies_dropdown' => $companies_dropdown
                                                ]);
    }

    public function store(Request $request,$slug){
       $request->validate([
           "new_company" => "required|exists:companies,id",
           "date" => "required|date",
           "old_company_id" => "required|exists:companies,id"
       ]);


      $transfer = LicenceTransfer::create([
        'licence_id'=> $request->licence_id,
        'company_id'=> $request->new_company,
        'old_company_id' => $request->old_company_id,
        'date' => $request->date,
        'status' => last($request->status),
        'slug' => $request->old_company.sha1(time())
       ]);

       if($transfer){
         Licence::where('company_id',$request->old_company_id)->update([
                                'company_id' => $request->new_company
         ]);
         return to_route('transfer_history',['slug' => $slug])->with('success','Licence transfered successfully.');
       }
       return to_route('transfer_history',['slug' => $slug])->with('errror','Oopps!!! An error occured while attempting licence transfer.');

      }
/**
       * View all licence transfer history for a certain licence
       */
      public function transferHistory(Request $request){
        $licence = Licence::with('transfers','old_company')->whereSlug($request->slug)->first();
        return Inertia::render('Licences/TransferHistory',['licence' => $licence]);
      }

      /**
       * View Individual licence transfer history
       */
      public function viewTransferedLicence($slug)
      {
        $view_transfer = LicenceTransfer::with('licence.company','licence.old_company')->whereSlug($slug)->first();
        $tasks = Task::where('model_type','Transfer')->where('model_id',$view_transfer->id)->whereUserId(auth()->id())->get();
        return Inertia::render('Licences/ViewTransferedLicence',['view_transfer' => $view_transfer,'tasks' => $tasks]);
      }

      public function update(Request $request)
      {
        $request->validate([
          'transfer_date'=> 'required|date'
        ]);
        $update = LicenceTransfer::whereSlug($request->slug)->update([
          'status' => last($request->status),
          'date' => $request->transfer_date
        ]);
        if ($update) {
          return back()->with('success','Licence transfer updated successfully.');
        }
        return back()->with('error','Error updating transfered licence.');
      }

      /**
       * Remove licence transfer
       */
      public function destroy($slug,$licence_slug){
        $licence = LicenceTransfer::whereSlug($slug)->first();
        if ($licence->delete()) {
          return to_route('transfer_history',['slug' => $licence_slug])->with('success','Licence transfer deleted successfully.');
        }
        return to_route('transfer_history',['slug' => $licence_slug])->with('error','Error deleting transfered licence.');
      }
}
