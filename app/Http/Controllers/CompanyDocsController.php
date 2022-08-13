<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocument;
use Illuminate\Http\Request;

class CompanyDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);
        
           $fileModel = new CompanyDocument;
           $fileName = $request->document->getClientOriginalName();
           $filePath = $request->file('document')->storeAs('companyDocuments', $fileName, 'public');
           $fileModel->document_name = $fileName;
           $fileModel->document_file = $fileName;
           $fileModel->company_id = $request->company_id;
           $fileModel->document_type = $request->doc_type;
           $fileModel->expiry_date = $request->expiry_date;
           $fileModel->file_path = 'storage/app/public/';
         
        if($fileModel->save()){
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
