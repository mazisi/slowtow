<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Licence;
use App\Models\LicenceDocument;
use App\Models\Nomination;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LiquorBoardRequest;
use App\Models\NominationDocument;
use Illuminate\Support\Facades\DB;

class NominationController extends Controller
{
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->firstOrFail();
        $nomination_years = Nomination::where('licence_id',$licence->id)->orderBy('year','DESC')->paginate(10)->withQueryString();
        $years = DB::table('years')->get()->pluck('year');//dropdown of years
        return Inertia::render('Nominations/Nominate',[
            'licence' => $licence,
            'nomination_years' => $nomination_years,
            'years' => $years
          ]);
    }


    public function store(Request $request){
        $request->validate([
            "year" => "required",
            "licence_id" => "required|exists:licences,id"
        ]);
        // $exist = Nomination::where('licence_id',$request->licence_id)
        //                             ->where('year',$request->year)->first();
        // if (!is_null($exist)) {
        //    return back()->with('error', 'Sorry...Nomination for '.$request->year.' already exist.');
        // }

        $nom = Nomination::create([
            "year" => $request->year,
            "licence_id" => $request->licence_id,
            "slug" => sha1(time())
        ]);

        if($nom){
           return to_route('view_nomination',['slug' => $nom->slug])->with('success','Nomination created successfully.');
         }
         return to_route('view_nomination',['slug' => $nom->slug])->with('error','Error creating nomination.');
    }
    /**
     * Get all nominations belonging to a certain licence.
     */
    public function nominations(Request $request){
        $nom = Licence::whereSlug($request->slug)->firstOrFail();
        $nominations = Nomination::with('licence','people')->where('licence_id',$nom->id)->get();
        return Inertia::render('Nominations/Nomination',['nominations' => $nominations]);
    }

    /**
     * Vue nominee.
     */
    public function viewIndividualNomination($slug){
        $latest_renewal_licence_doc = null;
        $nomination = Nomination::with('licence','people','merged_document','nomination_documents')->whereSlug($slug)->first();
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Nomination')->where('model_id',$nomination->id)->get();
        $latest_renewal_licence_doc = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$nomination->licence_id)->first(['document_file']);
        //if there is no original licence doc, get the licence issued
        if(is_null($latest_renewal_licence_doc)){
            $latest_renewal_licence_doc = LicenceDocument::where('document_type','Licence Issued')->where('licence_id',$nomination->licence_id)->first(['document_file']);
        }
        $nominees = People::pluck('full_name','id');
        $tasks = Task::where('model_type','Nomination')->where('model_id',$nomination->id)->latest()->paginate(4)->withQueryString();


return Inertia::render('Nominations/ViewIndividualNomination',[
        'nomination' => $nomination,
        'latest_renewal_licence_doc' => $latest_renewal_licence_doc,
        'nominees' => $nominees,
        'tasks' => $tasks,
        'liqour_board_requests' => $liqour_board_requests
    ]);
    }

    /**
     * Terminate individual.
     */
    public function terminate($id,$slug){
        $person = DB::table('nomination_people')
        ->whereId($id)
        ->update(['terminated_at' => now()]);

        if($person){
            return to_route('view-nomination',['slug' => $slug])->with('success','Individual updated succesfully.');
        }
         return back()->with('error','Error updating Individual.');
    }

    public function update(Request $request){
        try {
            $status = null;
            $request->validate([
                'nomination_id' => 'required|exists:nominations,id'
            ]);

        if($request->status){
            if($request->unChecked){
                $status = intval($request->status[0]) - 100;
            }else{
                $status = $request->status[0];
            }
        }
        Nomination::find($request->nomination_id)->update([
            "year" => $request->nomination_year,
            "status" => $status <= 0 ? NULL : $status,

        ]);
             return back()->with('success','Nomination updated succesfully.');


        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','Error updating nomination.');
        }
    }

    public function updateDate(Request $request, $slug){
        $stage = $request->stage;
        $fieldToUpdate = '';
        try {
            switch ($stage) {
                case 'Client Paid':
                    $fieldToUpdate = 'client_paid_date';
                    break;

                case 'Nomination Lodged':
                    $fieldToUpdate = 'nomination_lodged_at';
                    break;

                case 'Nomination Issued':
                    $fieldToUpdate = 'nomination_issued_at';
                    break;

                case 'Nomination Delivered':
                    $fieldToUpdate = 'nomination_delivered_at';
                    break;

                case 'Payment To The Liquor Board':
                    $fieldToUpdate = 'payment_to_liquor_board_at';
                    break;

                default:
                    // Handle the default case, if needed.
                    break;
            }

            Nomination::where('id', $request->model_id)->update([
                $fieldToUpdate => $request->dated_at
            ]);

            return back()->with('success', 'Date updated successfully.');

        } catch (\Throwable $th) {
            dd($th);
            return back()->with('error','Error updating date.');
        }

    }

    public function addSelectedNominees(Request $request){
        $nom = Nomination::find($request->nomination_id);
         foreach($request->selected_nominess as $selected){
            $exist = DB::table('nomination_people')
                         ->where('nomination_id',$nom->id)
                         ->where('people_id',$selected)
                         ->first();
            if(!is_null($exist)){
               continue;
            }
            $nom->people()->attach($selected);
         }
         return to_route('view_nomination',['slug' =>$nom->slug]);
    }

    public function detachNominee($nomination_id,$nominee_id){
        try {
            $nom = Nomination::find($nomination_id);
            $nom->people()->detach($nominee_id);
        return back()->with('success','Individual removed successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','Error removing individual.');
        }
    }

    public function uploadDocument(Request $request){
       
        $request->validate([
            "document_file"=> "required|mimes:pdf",
            "licence_id" => "required",
            "doc_type" => "required"
            ]);

                $removeSpace = str_replace(' ', '_',$request->document_file->getClientOriginalName());
                $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace);
                $request->file('document_file')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

               if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
                        $fileModel = new NominationDocument;
                        $fileModel->document_name = $request->document_file->getClientOriginalName();
                        $fileModel->document = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                        $fileModel->nomination_id = $request->licence_id;
                        $fileModel->doc_type = $request->doc_type;
                        $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;

                    if($fileModel->save()){
                        Nomination::whereId($fileModel->nomination_id)->update(['status' => $request->stage]);
                            return back()->with('success','Document uploaded successfully.');
                    }

               }else{
                   return back()->with('error','Azure storage could not be reached.Please try again.');
                 }

    }

    public function deleteDocument($id,$prevStage){
       try {
        $model = NominationDocument::find($id);
        if(!is_null($model->document)){
            $nom = Nomination::find($model->nomination_id);
            $nom->update(['status' => $prevStage]);
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
       } catch (\Throwable $th) {
        return back()->with('error','An unknown error occured.');
       }
    }

    public function destroy($licence_slug, $slug){
        try {
            Nomination::whereSlug($slug)->delete();
            return to_route('nominations',['slug' => $licence_slug])->with('success','Nomination deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','An unknown error occured.');
        }
    }
}
