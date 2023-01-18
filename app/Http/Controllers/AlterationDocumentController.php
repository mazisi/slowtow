<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AlterationDocument;

class AlterationDocumentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf",
            "doc_type"=> "required"
            ]);

            $fileModel = new AlterationDocument();
            $fileName = Str::limit(sha1(now()),7).str_replace(' ', '_',$request->document->getClientOriginalName());
            $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
            $fileModel->alteration_id = $request->alteration_id;
            $fileModel->doc_type = $request->doc_type;
            $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
            $fileModel->save();
       
      return back()->with('success','Document uploaded successfully.');
    
    }
    public function destroy($id)
    {
        try {
           AlterationDocument::find($id)->delete();
           return back()->with('success','Document deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','Error deleting document.');
        }
    }
}
