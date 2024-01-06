<?php

namespace App\Http\Controllers\Wholesale;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Http\Controllers\Controller;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

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
    public function generateLicenceIssuedDocs($licence)
    {
        try {
            $docs = LicenceDocument::where('licence_id', $licence->id)
                        ->where(function ($query) {
                            $query->where('document_type', 'NLA 6 Proposed')
                                ->orWhere('document_type', 'NLA 7 Submitted')
                                ->orWhere('document_type', 'NLA 8 Issued')
                                ->orWhere('document_type', 'NLA 9 Issued');
                        })->orderBy('num', 'desc')->get();

                    if($docs->count() >= 4){
                        $this->mergeDocuments($licence,$docs);
                    }
            
        } catch (\Throwable $th) {
            throw $th;
            //return back()->with('error','Error merging documents.');
        }
    }

    public function mergeDocuments($licence, $docs){
        try {       
           $merger = PDFMerger::init();
 
             foreach ($docs as $doc) {
               //$merger->addPDF(env('BLOB_FILE_PATH').$doc->document, 'all');
             }
 
             $fileName = 'merged_doc'.time().'.pdf';
             //$merger->merge();

            $exist = LicenceDocument::where('licence_id',$licence->id)->first();

                if($exist){
                    $exist->delete();
                }

             LicenceDocument::create([
                 'licence_id' => $licence->id,
                 'document_type' => 'Duplicate-Licence',
                 'document_name' => $fileName,
                 'document_file' => env('AZURE_STORAGE_CONTAINER') . '/' . $fileName
             ]);
            Licence::whereId($licence->id)->update(['merged_document' => $fileName]);
 
             //$merger->save(storage_path('/app/public/'.$fileName));
 
        //    if($store_merged_file){
        //      return back()->with('success','Document merged successfully.');
        //    }        
        //    return back()->with('error','Error uploading document.');
 
 
        } catch (\Throwable $th) {
            throw $th;
        //    return back()->with('error','Error uploading document.');
        }
 
     }
}
