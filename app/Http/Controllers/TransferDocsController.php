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

         $exist =  LicenceTransfer::whereId($request->transfer_id)->first();
 
        $merger = PDFMerger::init();
           
           if (! is_null($exist->merged_document)) {
             unlink(public_path('/storage/').$exist->file_name);
             $exist->delete();
           }
           $transfers =  TransferDocument::where('licence_transfer_id',$request->transfer_id)->orderby('num','ASC')->whereNotNull('num')->get();dd($transfers);
           foreach ($transfers as $transfer) {
             $merger->addPDF(public_path('/storage/').$transfer->document, 'all');
           }
           $fileName = time().'.pdf';
           $merger->merge();
 
           $exist->update(['merged_document' => $fileName]);
 
           $merger->save(public_path('/storage/'.$fileName));
           if($store_merged_file){
             return back()->with('success','Document merged successfully.');
           }        
           return back()->with('error','Error uploading document.');
 
     }

    public function destroy($id){
        $model = TransferDocument::find($id);
            // unlink(storage_path('app/folder/'.$model->document_file));
            unlink(public_path('storage/'.$model->document));
            $model->delete();
            return back()->with('success','Document removed successfully.');
      
    }

}
