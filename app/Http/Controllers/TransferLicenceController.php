<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Models\LiquorBoardRequest;
use App\Models\People;

class TransferLicenceController extends Controller
{
    public function index(Request $request){
        $licence = Licence::with('company','people')->whereSlug($request->slug)->first();
        $companies_dropdown = Company::where('id','!=',$licence->company_id)->pluck('name','id');
        $people_dropdown = People::where('id','!=',$licence->people_id)->pluck('full_name','id');
        $view = $licence->type == 'wholesale' ? 'Licences/TransferWholesaleLicence' : 'Licences/TransferLicence';

        return Inertia::render($view,
            ['licence' => $licence,
                'companies_dropdown' => $companies_dropdown,
                'people_dropdown'  => $people_dropdown
            ]);
    }

    public function store(Request $request,$slug){
      if($request->belongs_to === 'Individual'){
          $request->validate([
            "new_person" => "required|exists:people,id",
            "belongs_to" => "required|in:Individual,Company",
            "transfered_from" => "required|in:Individual,Company"
        ]);
      }else{
            $request->validate([
              "new_company" => "required|exists:companies,id",
              "belongs_to" => "required|in:Individual,Company",
              "transfered_from" => "required|in:Individual,Company"
          ]);
      }


      $sorted_statuses = Arr::sort($request->status);
      $transfer = LicenceTransfer::create([
        'transfered_to' => $request->belongs_to,
        'transfered_from' => $request->transfered_from,
        'licence_id'=> $request->licence_id,
        'old_people_id'=> $request->transfered_from ==='Individual' ? $request->old_company_id : NULL,
         'old_company_id' => $request->transfered_from === 'Company' ? $request->old_company_id : NULL,
        'company_id'=> $request->new_company,
        'people_id'=> $request->new_person,
        'status' => last($sorted_statuses),
        'slug' => sha1(time())
       ]);

       if($transfer){
                if($transfer->transfered_to === 'Company'){
                  Licence::whereId($transfer->licence_id)->update([
                    'company_id' => $request->new_company,
                    'people_id' => NULL,
                    'belongs_to' => 'Company'
                  ]);
                }else{
                  Licence::whereId($transfer->licence_id)->update([
                    'people_id' => $transfer->people_id,
                    'company_id' => NULL,
                    'belongs_to' => 'Individual'
                  ]);
                }

         return to_route('view_transfered_licence',['slug' => $transfer->slug])->with('success','Licence transfered successfully.');
       }
       return back()->with('errror','Oopps!!! An error occured while attempting licence transfer.');

      }
/**
       * View all licence transfer history for a certain licence
       */
      public function transferHistory(Request $request){
      $licence = Licence::whereSlug($request->slug)->first(['trading_name','slug']);

      $transfers = LicenceTransfer::with('old_person','new_person','old_company','new_company')
          ->whereHas('licence', function ($query) use($request) {
            $query->where('slug',$request->slug);
          })->paginate(15)->withQueryString();
        return Inertia::render('Licences/TransferHistory',['licence' => $licence, 'transfers' => $transfers]);
      }

      /**
       * View licence transfer individually.
       */
      public function viewTransferedLicence($slug){
        $view_transfer = LicenceTransfer::with('old_person','new_person','old_company','new_company','licence','transfer_documents')->whereSlug($slug)->first();
        $original_licence = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$view_transfer->licence_id)->first();

        // $companies_dropdown = Company::pluck('name','id');
        $tasks = Task::where('model_type','Transfer')->where('model_id',$view_transfer->id)->latest()->paginate(4)->withQueryString();

        $view = $view_transfer->licence->type == "wholesale" ? "Licences/ViewWholesaleTransferedLicence": "Licences/ViewTransferedLicence";

          return Inertia::render($view,
              ['view_transfer' => $view_transfer,
                  'tasks' => $tasks,
                  'original_licence' => $original_licence
              ]);
      }

      public function update(Request $request) {
       $status='';
        if($request->status){
          if($request->checked){
              $status = $request->status;
          }else{
              $status = $request->prevStage;
          }
      }
        $update = LicenceTransfer::whereSlug($request->slug)->update([
          'status' => $status
        ]);
        if ($update) {
          return back()->with('success','Licence transfer updated successfully.');
        }
        return back()->with('error','Error updating transfered licence.');
      }

      public function updateDates(Request $request, $slug){
        try {
          $fieldToUpdate = '';
          switch ($request->stage) {
            case 'Application Logded':
              $fieldToUpdate = 'lodged_at';
             break;
            case 'Activation Fee Paid':
             $fieldToUpdate = 'activation_fee_paid_at';
             break;
            case 'Transfer Issued':
              $fieldToUpdate = 'issued_at';
            break;
            case 'Transfer Delivered':
              $fieldToUpdate = 'delivered_at';
            break;
            case 'Payment To The Liquor Board':
              $fieldToUpdate = 'payment_to_liquor_board_at';
            break;
            default:
            return back()->with('error','Error.');
          }
        LicenceTransfer::whereSlug($slug)->update([$fieldToUpdate => $request->dated_at]);
         return back()->with('success','Date updated successfully.');
        } catch (\Throwable $th) {
          throw $th;
        }
      }

      /**
       * Remove licence transfer
       */
      public function destroy($slug,$licence_slug){
      try {
        $licence = LicenceTransfer::whereSlug($slug)->first();
        if ($licence->delete()) {
          return to_route('transfer_history',['slug' => $licence_slug])->with('success','Licence transfer deleted successfully.');
        }

      } catch (\Throwable $th) {
        //throw $th;
        return back()->with('error','Error deleting  licence transfer.');
      }
      }
}
