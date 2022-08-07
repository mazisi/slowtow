<?php

namespace App\Http\Controllers;

use App\Models\TemporalLicence;
use App\Models\TemporalLicenceDocument;
use Illuminate\Http\Request;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class TemporalLicenceDocsController extends Controller
{
   public function store(Request $request){
    $request->validate([
        "document"=> "required|mimes:pdf"
        ]);
        $get_file_name = explode(".",$request->document->getClientOriginalName());
        $store_file = $request->document->store('temp-licence-documents','public'); 
       TemporalLicenceDocument::create([
            "temporal_licence_id" => $request->temp_licence_id,
            "document_name" => $get_file_name[0],
            "document" => $store_file,
            "doc_type" => $request->doc_type,
            "num" => $request->merge_number,
            "belongs_to" => $request->person_or_company,
            'slug' => sha1(now())
           ]);
   
  return back()->with('success','Document uploaded successfully.');

}

public function destroy($id){
  $model = TemporalLicenceDocument::find($id);
 
      // unlink(storage_path('app/folder/'.$model->document_file));
      unlink(public_path('storage/'.$model->document));
      $model->delete();
      return back()->with('success','Document removed successfully.');

}

public function merge(Request $request,$type){

  $temporals = TemporalLicenceDocument::where('temporal_licence_id',$request->temporal_licence_id)
                                      ->where('belongs_to',$type)
                                      ->orderBy('num','ASC')->get();
dd($temporals);
     $merger = PDFMerger::init();

     foreach ($temporals as $temp) {
       $merger->addPDF(public_path('/storage/').$temp->document, 'all');
     }
     $fileName = time().'.pdf';
     $merger->merge();

     $merger->save(public_path('/storage/'.$fileName));
     $updateModel = TemporalLicence::whereId($request->temporal_licence_id)->update(['company_merged_document' => $fileName]);
     if($updateModel){
       return back()->with('success','Document merged successfully.');
     }        
     return back()->with('error','Error uploading document.');

}
   
}
