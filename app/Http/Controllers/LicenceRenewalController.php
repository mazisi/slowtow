<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Events\LogUserActivity;
use App\Models\RenewalDocument;
use App\Models\LiquorBoardRequest;
use Illuminate\Support\Facades\DB;

class LicenceRenewalController extends Controller
{
    public function renewLicence(Request $request){
        $licence = Licence::with('company')->whereSlug($request->slug)->first();
        $renewals = LicenceRenewal::with('licence')->where('licence_id',$licence->id)->orderByDesc('date')->paginate(10)->withQueryString();
         $years = DB::table('years')->orderBy('year', 'desc')->get()->pluck('year');
        return Inertia::render('Renewals/Renewals',[
        'licence' => $licence,
        'renewals' => $renewals,
        'years' => $years]);
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
         return to_route('view_licence_renewal',['slug' => $renew->slug ])->with('success','Licence renewed successfully.');
        }
        return back()->with('error','An error occured while renewing licence.');
    }


    public function show($slug){
        $renewal = LicenceRenewal::with('licence', 'renewal_documents')->whereSlug($slug)->first();
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Licence Renewal')->where('model_id',$renewal->id)->get();

        $tasks = Task::where('model_id',$renewal->id)->where('model_type','Licence Renewal')->latest()->paginate(4)->withQueryString();
        return Inertia::render('Renewals/ViewLicenceRenewal',[
            'renewal' => $renewal,
            'tasks'  => $tasks,

            'liqour_board_requests' => $liqour_board_requests
        ]);
    }



    public function update(Request $request){
       $request->validate([
        'renewal_id' => 'required'
       ]);

       $ren = LicenceRenewal::find($request->renewal_id);
       $status = null;
        if($request->status){
            if($request->unChecked){
                $status = $request->status[0] - 1;
            }else{
                $status = $request->status[0];
            }
        }

       $ren->update([
        'date' => $request->year,
        'status' => $status <= 0 ? NULL : $status,

       ]);
       if($ren){
        return back()->with('success','Renewal updated successfully.');
       }
       return back()->with('error','Sorry..An error occured.');
    }

    public function uploadDocuments(Request $request){
        try {
            $request->validate([
                "document_file"=> "required|mimes:pdf"
                ]);

                $renewal_id = $request->licence_id;

                $removeSpace = str_replace(' ', '_',$request->document_file->getClientOriginalName());
                $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace);
                $request->file('document_file')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));


    // check if file saved in azure todo mazisi
                $fileModel = new RenewalDocument;
                $fileModel->document_name = $request->document_file->getClientOriginalName();
                $fileModel->document = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                $fileModel->licence_renewal_id = $renewal_id;
                $fileModel->doc_type = $request->doc_type;
                $fileModel->date = $request->date; //check this date.
                $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;

                if($fileModel->save()){
                    LicenceRenewal::whereId($fileModel->licence_renewal_id)->update(['status' => $request->stage]);
                   return back()->with('success','Document uploaded successfully.');
                 }


        } catch (\Throwable $th) {
            //throw $th;
           return back()->with('error','Error uploading document.');
        }

    }

    public function updateDates(Request $request, $slug){
        $stage = $request->stage;
        $fieldToUpdate = '';
            try {
                switch ($stage) {
                    case 'Client Paid':
                        $fieldToUpdate = 'client_paid_at';
                        break;

                    case 'Payment To The Liquor Board':
                        $fieldToUpdate = 'payment_to_liquor_board_at';
                        break;

                    case 'Renewal Issued':
                        $fieldToUpdate = 'renewal_issued_at';
                        break;

                    case 'Renewal Delivered':
                        $fieldToUpdate = 'renewal_delivered_at';
                        break;

                    default:
                        // Handle the default case, if needed.
                        break;
                }

                LicenceRenewal::where('id', $request->renewal_id)->update([
                    $fieldToUpdate => $request->dated_at
                ]);

                return back()->with('success', 'Date updated successfully.');

            } catch (\Throwable $th) {
                return back()->with('error','Error updating date.');
            }
      }

    public function deleteDocument($id){
            try {
                $model = RenewalDocument::find($id);
                $activity = 'Deleted Licence Renewal Document: ' . $model->document_name;
                event(new LogUserActivity(auth()->user(), $activity));
                if($model->delete()){
                    return back()->with('success','Document removed successfully.');
                }
            } catch (\Throwable $th) {
                return back()->with('error','Error deleting document.');
            }

    }

    public function destroy($licence_slug, $slug){
        try {
            LicenceRenewal::whereSlug($slug)->delete();

            return to_route('renew_licence',['slug' => $licence_slug])->with('success','Renewal deleted successfully.');

        } catch (\Throwable $th) {
            return to_route('renew_licence',['slug' => $licence_slug])->with('error','Error deleting renewal.');
        }

    }

}
