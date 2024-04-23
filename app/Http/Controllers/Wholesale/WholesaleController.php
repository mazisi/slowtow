<?php

namespace App\Http\Controllers\Wholesale;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Licence;
use App\Models\LicenceType;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Http\Controllers\Controller;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class WholesaleController extends Controller
{

    public function view_licence(Request $request)
    {

        $licence = Licence::with('company', 'people', 'licence_documents','duplicate_originals.duplicate_documents')
            ->whereSlug($request->slug)
            ->first();
        
       
         

// $original_lic_delivered now contains the desired document if found

        $duplicate_original_issued = json_decode(getLicenceDocs($licence))->duplicate_original_issued;
        $original_lic_delivered = json_decode(getLicenceDocs($licence))->original_licence_delivered;
   

        $companies = Company::pluck('name', 'id');
        $people = People::pluck('full_name', 'id');
        $licence_dropdowns = LicenceType::orderBy('licence_type')->get();
        $tasks = Task::where('model_type', 'Licence')
            ->where('model_id', $licence->id)
            ->latest()
            ->paginate(4)
            ->withQueryString();


        $view = (is_null($licence->is_new_app)||$licence->is_new_app==1)? 'ViewNewApp' : 'ViewWholesaleLicence';
        
        return Inertia::render('Licences/' . $view, [
            'licence' => $licence,
            'licence_dropdowns' => $licence_dropdowns,
            'tasks' => $tasks,
            'companies' => $companies,
            'people' => $people,
            'duplicate_original_lic' => $duplicate_original_issued,
            'original_lic_delivered' => $original_lic_delivered,
        ]);
    }


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

            $fileName = 'Original-Licence'.$licence->id;
             
            $exist = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->first();

                if($exist){
                    //$exist->delete();
                    return;
                }else{

           $merger = PDFMerger::init();
 
             foreach ($docs as $doc) {
               $merger->addPDF(env('BLOB_FILE_PATH').$doc->document, 'all');
             }
 
             
             LicenceDocument::create([
                 'licence_id' => $licence->id,
                 'document_type' => 'Original-Licence',
                 'document_name' => $fileName,
                 'document_file' => env('AZURE_STORAGE_CONTAINER') . '/' . $fileName.'.pdf'
             ]);
            Licence::whereId($licence->id)->update(['merged_document' => $fileName]);
            $merger->merge();
            $merger->save(storage_path('/app/public/'.$fileName));
 
              return back()->with('success','Document merged successfully.');
          
        }
 
        } catch (\Throwable $th) {
            throw $th;
        //    return back()->with('error','Error uploading document.');
        }
 
     }
}
