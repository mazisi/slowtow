<?php

namespace App\Http\Controllers;

use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use Illuminate\Http\Request;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

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

    public function merge(Request $request){

         $nominations =  LicenceTransfer::whereId($request->transfer_id)->first();
 
           $merger = PDFMerger::init();
 //update licence transfer 'merged doc field
 //if it exists replace it
           $exist = MergedDocument::where('nomination_id',$id)->first();
           if ($exist) {
             unlink(storage_path('/app/public/'.$exist->file_name));
             $exist->delete();
           }
 
           foreach ($nominations as $nom) {
             $merger->addPDF(storage_path('/app/public/').$nom->document, 'all');
           }
           $fileName = time().'.pdf';
           $merger->merge();
 
           $store_merged_file = MergedDocument::create([
             'file_name' => $fileName,'nomination_id' => $id]);
 
           $merger->save(storage_path('/app/public/'.$fileName));
           if($store_merged_file){
             return back()->with('success','Document merged successfully.');
           }        
           return back()->with('error','Error uploading document.');
 
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
