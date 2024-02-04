<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AdditionalDoc;

class AdditionalDocsController extends Controller
{
    function store(Request $request) {
        
        $request->validate([
            'description' => 'required',
            // 'document' => 'mimes:pdf',
            'uploaded_at' => 'required',
            "licence_id" => "required|exists:licences,id"
            ]);

if($request->hasFile('document')){

            $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
            $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace); 
            $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

   if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
                    $fileModel = new AdditionalDoc;
                    $fileModel->description = $request->description;
                    $fileModel->document_name = $removeSpace;
                    $fileModel->uploaded_at = $request->uploaded_at;
                    $fileModel->licence_id = $request->licence_id;
                    $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                    
            if($fileModel->save()){
                return back()->with('success','Document uploaded successfully.');
            }
                return back()->with('error','Error uploading document.');
    }else{
        return back()->with('error','Azure storage could not be reached.Please try again.');
      }
     
    }
    AdditionalDoc::create([
        'description' => $request->description,
        'uploaded_at' => $request->uploaded_at,
        'licence_id' => $request->licence_id
    ]);
    return back()->with('success','Saved successfully.');
}

    function destroy($id) {
        try {
            AdditionalDoc::find($id)->delete();
            return back()->with('success','Document deleted successfully.');
        } catch (\Throwable $th) {
            throw $th;
            return back()->with('error','Error deleting document.');
        }
        

    }
}
