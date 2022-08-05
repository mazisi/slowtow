<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;

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

      $sorted_statuses = Arr::sort($request->status);
      $transfer = LicenceTransfer::create([
        'licence_id'=> $request->licence_id,
        'company_id'=> $request->new_company,
        'old_company_id' => $request->old_company_id,
        'date' => $request->date,
        'status' => last($sorted_statuses),
        'slug' => $request->old_company.sha1(time())
       ]);

       if($transfer){
         Licence::where('company_id',$request->old_company_id)->update([
                          'company_id' => $request->new_company
         ]);
         return to_route('view_transfered_licence',['slug' => $transfer->slug])->with('success','Licence transfered successfully.');
       }
       return back()->with('errror','Oopps!!! An error occured while attempting licence transfer.');

      }
/**
       * View all licence transfer history for a certain licence
       */
      public function transferHistory(Request $request){
        $licence = Licence::with('transfers','old_company')->whereSlug($request->slug)->first();
        return Inertia::render('Licences/TransferHistory',['licence' => $licence]);
      }

      /**
       * View Individual licence transfer individually.
       */
      public function viewTransferedLicence($slug){
        $view_transfer = LicenceTransfer::with('licence.company','licence.old_company')->whereSlug($slug)->first();
        $companies_dropdown = Company::pluck('name','id');
        $tasks = Task::where('model_type','Transfer')->where('model_id',$view_transfer->id)->whereUserId(auth()->id())->get();

        $old_transfer_forms = TransferDocument::where('doc_type','Transfer Forms')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_transfer_forms = TransferDocument::where('doc_type','Transfer Forms')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $smoking_affidavict = TransferDocument::where('doc_type','Smoking Affidavit')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $old_poa_res_docs = TransferDocument::where('doc_type','POA & RES')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_poa_res_docs = TransferDocument::where('doc_type','POA & RES')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $old_shareholding = TransferDocument::where('doc_type','Shareholding')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_shareholding = TransferDocument::where('doc_type','Shareholding')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $old_cipc_certificate = TransferDocument::where('doc_type','CIPC Certificate')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_cipc_certificate = TransferDocument::where('doc_type','CIPC Certificate')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $company_docs = TransferDocument::where('doc_type','Company Documents')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $id_docs = TransferDocument::where('doc_type','ID Documents')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $police_clearance = TransferDocument::where('doc_type','Police Clearance')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $tax_clearance = TransferDocument::where('doc_type','Police Clearance')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $lta_certificate = TransferDocument::where('doc_type','LTA Certificate')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $financial_interest = TransferDocument::where('doc_type','Financial Interests')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $landloard_letter = TransferDocument::where('doc_type','Lease/Landlord Letter')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $representation = TransferDocument::where('doc_type','Representation')->where('licence_transfer_id',$view_transfer->id)->first();
        $index_page = TransferDocument::where('doc_type','Index page')->where('licence_transfer_id',$view_transfer->id)->first();
        $client_quoted = TransferDocument::where('doc_type','Client Quoted')->where('licence_transfer_id',$view_transfer->id)->first();
        $client_invoiced = TransferDocument::where('doc_type','Client Invoiced')->where('licence_transfer_id',$view_transfer->id)->first();
        $payment_to_liquor_board = TransferDocument::where('doc_type','Payment To The Liquor Board')->where('licence_transfer_id',$view_transfer->id)->first();
        $transfer_logded = TransferDocument::where('doc_type','Transfer Logded')->where('licence_transfer_id',$view_transfer->id)->first();
        $activation_fee =  TransferDocument::where('doc_type','Activation Fee Paid')->where('licence_transfer_id',$view_transfer->id)->first();
        $transfer_issued = TransferDocument::where('doc_type','Transfer Issued')->where('licence_transfer_id',$view_transfer->id)->first();
        $transfer_delivered = TransferDocument::where('doc_type','Transfer Delivered')->where('licence_transfer_id',$view_transfer->id)->first();
        return Inertia::render('Licences/ViewTransferedLicence',
        ['view_transfer' => $view_transfer,
                 'tasks' => $tasks,
          'companies_dropdown' => $companies_dropdown,

          'old_transfer_forms' =>$old_transfer_forms,
          'current_transfer_forms' => $current_transfer_forms,
          'smoking_affidavict' => $smoking_affidavict,
          'old_poa_res_docs' => $old_poa_res_docs,
          'current_poa_res_docs' => $current_poa_res_docs,
          'old_shareholding' => $old_shareholding,
          'current_shareholding' => $current_shareholding,
          'old_cipc_certificate' => $old_cipc_certificate,
          'current_cipc_certificate' => $current_cipc_certificate,
          'company_docs' => $company_docs,
          'id_docs' => $id_docs,
          'police_clearance' => $police_clearance,
          'tax_clearance' => $tax_clearance,
          'lta_certificate' => $lta_certificate,
          'financial_interest' => $financial_interest,
          'landloard_letter' => $landloard_letter,
          'representation' => $representation,
          'index_page' => $index_page,
          'client_quoted' => $client_quoted,
          'client_invoiced' => $client_invoiced,
          'payment_to_liquor_board' => $payment_to_liquor_board,
          'transfer_logded' => $transfer_logded,
          'activation_fee' => $activation_fee,
          'transfer_issued' => $transfer_issued,
          'transfer_delivered' => $transfer_delivered
        ]);
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
