<?php

namespace App\Http\Controllers;

use App\Models\TemporalLicenceDocument;
use Illuminate\Http\Request;

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
   
}
