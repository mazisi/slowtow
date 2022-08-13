<?php

namespace App\Http\Controllers;

use App\Models\LicenceDocument;
use Illuminate\Http\Request;

class LicenceDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "original_licence_doc"=> "required|mimes:pdf"
            ]);

            $fileModel = new LicenceDocument;
            $fileName = $request->original_licence_doc->getClientOriginalName();
            $filePath = $request->file('original_licence_doc')->storeAs('licenceDocuments', $fileName, 'public');
            $fileModel->document_name = $fileName;
            $fileModel->document_file = $fileName;
            $fileModel->licence_id = $request->licence_id;
            $fileModel->document_type = $request->doc_type;
            $fileModel->save();

       
      return back()->with('success','Document uploaded successfully.');
    
    }

    public function destroy($id){
        $model = LicenceDocument::find($id);
        if(!is_null($model->document_file)){
            // unlink(storage_path('app/folder/'.$model->document_file));
            unlink(public_path('storage/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }
}
