<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocument;
use App\Models\PeopleDocument;
use App\Models\TemporalLicence;
use App\Models\TemporalLicenceDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use File;

class TemporalLicenceDocsController extends Controller
{
   public function store(Request $request){
    $request->validate([
        "document"=> "required|mimes:pdf"
        ]); 

        try {
            $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
            $fileModel = new TemporalLicenceDocument;
            $fileName = Str::limit(sha1(now()),7).str_replace(' ', '_',$removeSpace);
            $filePath = $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
            $fileModel->document_name = $fileName;
            $fileModel->document = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
            $fileModel->temporal_licence_id = $request->temp_licence_id;
            $fileModel->doc_type = $request->doc_type;
            $fileModel->num = $request->merge_number;
            $fileModel->belongs_to = $request->person_or_company;
            $fileModel->slug = sha1(now());
            
      if($fileModel->save()){
        return back()->with('success','Document uploaded successfully.');
      }
      return back()->with('error','Error uploading document.');
        } catch (\Throwable $th) {
          return back()->with('error','An unknown error while uploading document.');
        }
        

}

public function destroy($id){
  $model = TemporalLicenceDocument::find($id);
 
      $model->delete();
      return back()->with('success','Document removed successfully.');

}

public function merge(Request $request,$type){

  // $get_proof_of_payment = TemporalLicenceDocument::where('temporal_licence_id',$request->temporal_licence_id)
  //                                     ->where('doc_type','Payment To The Liquor Board')->first();
  // $get_proof_of_payment->update(['num' => 2]);

  if($type == 'Individual'){
    $person_id_document = PeopleDocument::where('people_id',$request->person_id)->where('doc_type','ID Document')->first();
   if(!is_null($person_id_document)){
    File::copy(env('BLOB_FILE_PATH').$person_id_document->path, env('BLOB_FILE_PATH').$person_id_document->path);
          $fileModel = new TemporalLicenceDocument;       
            $fileModel->document_name = $person_id_document->document_name;
            $fileModel->document = $person_id_document->document;
            $fileModel->temporal_licence_id = $request->temporal_licence_id;
            $fileModel->doc_type = 'Individual ID Document';
            $fileModel->num = 2;
            $fileModel->belongs_to = 'Individual';
            $fileModel->slug = sha1(now());
            $fileModel->save();

  }else{
    return back()->with('error','ID document is not yet uploaded.');
  }
}

  $temporals = TemporalLicenceDocument::where('temporal_licence_id',$request->temporal_licence_id)
                                      ->where('belongs_to',$type)
                                      ->whereNotNull('num')
                                      ->orderBy('num','ASC')->get();

     $merger = PDFMerger::init();

     foreach ($temporals as $temp) {
       $merger->addPDF(env('BLOB_FILE_PATH').$temp->document, 'all');
     }
     $fileName = time().'.pdf';
     $merger->merge();

     $merger->save(storage_path('/app/public/'.$fileName));
     $updateModel = TemporalLicence::whereId($request->temporal_licence_id)->update(['merged_document' => $fileName]);
     if($updateModel){
       return back()->with('success','Document merged successfully.');
     }        
     return back()->with('error','Error uploading document.');

}
   
}
