<?php

namespace App\Http\Controllers;

use File;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class LicenceDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "doc"=> "required|mimes:pdf"
            ]);

            $fileModel = new LicenceDocument;
            $fileName = $request->doc->getClientOriginalName();
            $request->file('doc')->storeAs('licenceDocuments', $fileName, 'public');
            $fileModel->document_name = $fileName;
            $fileModel->licence_id = $request->licence_id;
            $fileModel->document_type = $request->doc_type;
            $fileModel->num = $request->num;
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
            unlink(public_path('storage/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }

    public function merge($licence_id){

      $exist =  Licence::whereId($licence_id)->first(); 
       $merger = PDFMerger::init();           
          if (! is_null($exist->merged_document)) {
            unlink(public_path('/storage/').$exist->merged_document);
            $exist->update(['merged_document' => null]);
          }
                  
         $all_docs = LicenceDocument::where('licence_id',$licence_id)->whereNotNull('num')->orWhere('document_type','Payment To The Liquor Board')->orderBy('num','ASC')->get();
         foreach ($all_docs as $doc) {
            $merger->addPDF(public_path('/storage/licenceDocuments/').$doc->document_name, 'all');
          }
          $fileName = 'licence'.'_'.time().'.pdf';
          $merger->merge();

          $exist->update(['merged_document' => $fileName]);

          $merger->save(public_path('/storage/'.$fileName));
          return back()->with('success','Document merged successfully.');
          

    }
}
