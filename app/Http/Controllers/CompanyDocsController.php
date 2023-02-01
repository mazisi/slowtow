<?php

namespace App\Http\Controllers;

use App\Models\CompanyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);
        
           $fileModel = new CompanyDocument;
           $fileName = Str::limit(sha1(now()),7).str_replace(' ', '_',$request->file_name);
           $filePath = $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
           $fileModel->document_name = $fileName;
           $fileModel->document_file = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
           $fileModel->company_id = $request->company_id;
           $fileModel->document_type = $request->doc_type;
           $fileModel->expiry_date = $request->expiry_date;
           $fileModel->file_path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
         
        if($fileModel->save()){
            return back()->with('success','Document uploaded successfully.');
        }
        return back()->with('error','Error uploading documents.');
    
    }

    public function destroy($id){
        $model = CompanyDocument::find($id);
        if(!is_null($model->document_file)){
            //unlink(public_path('storage/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }
}
