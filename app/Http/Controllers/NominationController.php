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
        // $nomination_years = Nomination::paginate(10)->withQueryString();
        $nomination_years = Nomination::latest()->where('licence_id',$licence->id)->get();
        $years = DB::table('years')->get()->pluck('year');//dropdown of years
        return Inertia::render('Nominations/Nominate',[
            'licence' => $licence,
            'nomination_years' => $nomination_years,
            'years' => $years
          ]);
    }
    /**
     * Insert nomination.
     * Loop through selected people IDs & insert to database.
     */
    public function store(Request $request){
        $request->validate([
            "year" => "required",
            "licence_id" => "required|exists:licences,id"
        ]);
        $exist = Nomination::where('licence_id',$request->licence_id)
                                    ->where('year',$request->year)->first();
        if (!is_null($exist)) {
           return back()->with('error', 'Sorry...Nomination for '.$request->year.' already exist.');
        }

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
        $nomination = Nomination::with('licence','people','merged_document')->whereSlug($slug)->first();
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Nomination')->where('model_id',$nomination->id)->get();
        $nominees = People::pluck('full_name','id');
        $tasks = Task::where('model_type','Nomination')->where('model_id',$nomination->id)->whereUserId(auth()->id())->get();
        
$client_quoted = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Client Quoted')->first();
$client_invoiced = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Client Invoiced')->first();
$liquor_board = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Payment To The Liquor Board')->first();
$nomination_forms = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Forms')->first();
$proof_of_payment = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Proof of Payment')->first();
$attorney_doc = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Power of Attorney')->first();
$certified_id_doc =  NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','ID Document')->first();
$police_clearance_doc = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Police Clearances')->first();
$latest_renewal_doc = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Latest Renewal/Licence')->first();
$nomination_logded = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Lodged')->first();
$scanned_app = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Scanned Application')->first();
$nomination_issued = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Issued')->first();
$nomination_delivered = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Delivered')->first();
$latest_renewal_licence_doc = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$nomination->licence_id)->first(['document_file']);

return Inertia::render('Nominations/ViewIndividualNomination',[
        'nomination' => $nomination,
        'nominees' => $nominees,
        'tasks' => $tasks,
        'client_quoted' => $client_quoted,
        'client_invoiced' => $client_invoiced,
            'liquor_board' => $liquor_board,
            'nomination_forms' => $nomination_forms,
            'proof_of_payment' => $proof_of_payment,
            'attorney_doc' => $attorney_doc,
            'certified_id_doc' => $certified_id_doc,
            'police_clearance_doc' => $police_clearance_doc,
            'latest_renewal_doc' => $latest_renewal_doc,
            'nomination_logded' => $nomination_logded,
            'nomination_issued' => $nomination_issued,
            'nomination_delivered' => $nomination_delivered,
            'scanned_app' => $scanned_app,
            'latest_renewal_licence_doc' => $latest_renewal_licence_doc,
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
            return to_route('view-nomination',['slug' => $slug])->with('success','Person updated succesfully.');
        }
         return back()->with('error','Error updating person.');
    }

    public function update(Request $request){
        $status = null;
        $request->validate([
            'nomination_year' => 'required',
            'nomination_id' => 'required|exists:nominations,id'
        ]);
        $nom= Nomination::find($request->nomination_id);
        if(!is_null($nom->status) && empty($request->status)){
            $db_status = $nom->status;
            $status = $db_status;
        }elseif(!empty($request->status)){
            $sorted_statuses = Arr::sort($request->status);
            $status = last($sorted_statuses);
        }
        $nom->update([
            "year" => $request->nomination_year,
            "status" => $status,
            "client_paid_date" => $request->client_paid_date,
            "nomination_lodged_at" => $request->nomination_lodged_at,
            "nomination_issued_at" => $request->nomination_issued_at,
            "nomination_delivered_at" => $request->nomination_delivered_at,
            "payment_to_liquor_board_at" => $request->payment_to_liquor_board_at
            
        ]);
        if($nom){
           return back()->with('success','Nomination updated succesfully.');
        }
        return back()->with('error','Error updating nomination.');
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
        $nom = Nomination::find($nomination_id);
        $nom->people()->detach($nominee_id);
        return back();
    }
    
    public function uploadDocument(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);

           $fileModel = new NominationDocument;
           $fileName = str_replace(' ', '_',$request->document->getClientOriginalName());
           $filePath = $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
           $fileModel->document_name = $fileName;
           $fileModel->document = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
           $fileModel->nomination_id = $request->nomination_id;
           $fileModel->doc_type = $request->doc_type;
           $fileModel->date = $request->date;
           $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;

       if($fileModel->save()){
            return back()->with('success','Document uploaded successfully.');
       }
       return back()->with('error','Error uploading document.');
    }

    public function deleteDocument($id){
       try {
        $model = NominationDocument::find($id);
        if(!is_null($model->document)){
           // unlink(storage_path('/app/public/'.$model->document));
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
