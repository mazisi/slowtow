<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Models\LicenceDocument;
use Illuminate\Http\Request;

class LicenceDocsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "original_licence"=> "required|file",
            "licence_doc_name" => "required|string|max:255"
        ]);
        $store_file = $request->original_licence->store('licence-docs','public');
        
        $doc = LicenceDocument::create([
         "licence_id" => $request->licence_id,
         "document_name" => $request->licence_doc_name,
         "document_file" => $store_file,
         "document_type" => "Original Licence"
        ]);
        $q = Licence::whereId($request->licence_id)->first();
        if($doc){
            return redirect(route('view_licence',["slug" => $q->slug]))->with('success','Licence document uploaded successfully.');
        }
        return redirect(route('view_licence',["slug" => $q->slug]))->with('error','Error uploading file.');
    }
}
