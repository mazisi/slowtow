<?php

namespace App\Http\Controllers\Wholesale;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LicenceDocument;

class WholesaleController extends Controller
{
    public function show(Request $request){
        $licence = Licence::with('company','licence_stage_dates','documents')->whereSlug($request->slug)->first();
      
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString(); 

        return Inertia::render('Wholesale/ViewWholesale',[
            'licence' => $licence,
            'tasks' => $tasks]);
    }

    /**
       * Original Licence stage will display a combined PDF document. The documents uploaded
       * for Stages 9, 10, 11 and 13 must be merged into one and saved under this
       * stage. The order of the documents should be Stage 13  Stage 11  Stage
       * 10  Stage 9.
     */
    public function generateLicenceIssuedDocs($licence_id)
    {
        try {
            $docs = LicenceDocument::where('licence_id', $licence_id)
                        ->where(function ($query) {
                            $query->where('document_type', 'NLA 6 Proposed')
                                ->orWhere('document_type', 'NLA 7 Submitted')
                                ->orWhere('document_type', 'NLA 8 Issued')
                                ->orWhere('document_type', 'NLA 9 Issued');
                        })->orderBy('num', 'desc')->get();

           // $this->deleteExistingMergedDocument($docs);

            $mergedDocument = $this->mergeDocuments($docs);

            //$this->updateMergedDocument($docs, $mergedDocument);
            
        } catch (\Throwable $th) {
            throw $th;
            //return back()->with('error','Error merging documents.');
        }
    }
}
