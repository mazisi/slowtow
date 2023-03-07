<?php

namespace App\Http\Controllers;


use App\Models\Licence;
use File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
//use Illuminate\Http\File;
use App\Models\LicenceDocument;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class LicenceDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "doc"=> "required|mimes:pdf"
            ]);
            
            $removeSpace = str_replace(' ', '_',$request->doc->getClientOriginalName());
            
            $fileModel = new LicenceDocument;
            $fileName = Str::limit(sha1(now()),4).str_replace('-', '_',$removeSpace);
            $request->file('doc')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
            $fileModel->document_name = $request->doc->getClientOriginalName();
            $fileModel->licence_id = $request->licence_id;
            $fileModel->document_type = $request->doc_type;
            $fileModel->num = $request->num;
            $fileModel->document_file = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
            $fileModel->save();

            $updateLicence = Licence::find($request->licence_id);
            switch ($request->doc_type) {
              case 'Client Quoted':
                $updateLicence->update(['is_client_invoiced' => $fileModel->document_name]);
                break;
              case 'Application Lodged':
                $updateLicence->update(['is_application_logded_doc_uploaded' => true]);
                break;
              case 'Client Finalisation Invoiced':
                $updateLicence->update(['is_finalisation_doc_uploaded' => $fileModel->document_name]);
                break;                
              default:
                # code...
                break;
            }

       
      return back()->with('success','Document uploaded successfully.');
    
    }

    public function destroy($id){
        $model = LicenceDocument::find($id);
        $updateLicence = Licence::find($model->licence_id);
        switch ($model->document_type) {
          case 'Client Quoted':
            $updateLicence->update(['is_client_invoiced' => null]);
            break;
          case 'Application Lodged':
            $updateLicence->update(['is_application_logded_doc_uploaded' => null]);
            break;
          case 'Client Finalisation Invoiced':
            $updateLicence->update(['is_finalisation_doc_uploaded' => null]);
            break;               
          default:
            # code...
            break;
        }
        if(!is_null($model->document_file)){
            //unlink(public_path('storage/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
        
             return back()->with('error','Document not successfully.');
    }

    public function merge($licence_id){

      $exist =  Licence::whereId($licence_id)->first(); 
       $merger = PDFMerger::init();           
          if (! is_null($exist->merged_document)) {
            unlink(storage_path().'app/public/'.$exist->merged_document);
            $exist->update(['merged_document' => null]);
          }
                  
         $all_docs = LicenceDocument::where('licence_id',$licence_id)->whereNotNull('num')->orWhere('document_type','Payment To The Liquor Board')->orderBy('num','ASC')->get();
         foreach ($all_docs as $doc) {
            $merger->addPDF(env('BLOB_FILE_PATH').$doc->document_name, 'all');
          }
          $fileName = 'licence'.'_'.time().'.pdf';
          $merger->merge();

          $exist->update(['merged_document' => $fileName]);

          $merger->save(storage_path('/app/public/'.$fileName));
          return back()->with('success','Document merged successfully.');
          

    }
}
