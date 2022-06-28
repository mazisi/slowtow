<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Models\LicenceDocument;
use Illuminate\Http\Request;

class LicenceDocsController extends Controller
{
    public function store(Request $request){

        $request->validate([
            "original_licence_doc*"=> "required|mimes:pdf",
            "doc_name" => "required|string|max:255",
            ]);
        
        foreach ($request->original_licence_doc as $doc) {
            $store_file = $doc->store('licenceDocuments','public'); 
           LicenceDocument::create([
                "licence_id" => $request->licence_id,
                "document_name" => $request->doc_name,
                "document_file" => $store_file,
                "document_type" => $request->doc_type
               ]);
        }  
      return back()->with('success','Document uploaded successfully.');
    
    }
}
