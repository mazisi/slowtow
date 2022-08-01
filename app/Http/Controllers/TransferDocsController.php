<?php

namespace App\Http\Controllers;

use App\Models\TransferDocument;
use Illuminate\Http\Request;

class TransferDocsController extends Controller
{
    public function store(Request $request,$transfer_id){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);
            $get_file_name = explode(".",$request->document->getClientOriginalName());
            $store_file = $request->document->store('transferDocuments','public'); 
           TransferDocument::create([
                "licence_transfer_id" => $transfer_id,
                "document_name" => $get_file_name[0],
                "document" => $store_file,
                "doc_type" => $request->document_type,
                "num" => $request->document_number,
                "belongs_to" => $request->belong_to,
                'slug' => sha1(now())
               ]);
       
      return back()->with('success','Document uploaded successfully.');
    
    }

    public function destroy($id){
        $model = TransferDocument::find($id);
        if(!is_null($model->document_file)){
            // unlink(storage_path('app/folder/'.$model->document_file));
            unlink(public_path('storage/app/public/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }

}
