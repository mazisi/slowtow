<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocument;
use Illuminate\Http\Request;

class CompanyDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf",
            "doc_name" => "required|string|max:255",
            ]);
        
     
           $store_file = $request->document->store('companyDocuments','public'); 
           $comp = CompanyDocument::create([
                "company_id" => $request->company_id,
                "document_name" => $request->doc_name,
                "document_file" => $store_file,
                "document_type" => $request->doc_type,
                "expiry_date" => $request->expiry_date,
                "file_path" => 'storage/app/public/'
               ]);
        if($comp){
            return back()->with('success','Document uploaded successfully.');
        }
        return back()->with('error','Error uploading documents.');
    
    }

    public function destroy($id){
        $model = CompanyDocument::find($id);
        if(!is_null($model->document_file)){
            unlink(public_path('storage/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }
}
