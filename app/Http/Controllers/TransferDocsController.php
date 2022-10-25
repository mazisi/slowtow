<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use File;

class TransferDocsController extends Controller
{
    public function store(Request $request,$transfer_id){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);
            $fileModel = new TransferDocument;
            $fileName = $request->document->getClientOriginalName();
            $filePath = $request->file('document')->storeAs('transferDocuments', $fileName, 'public');
            $fileModel->document_name = $request->document->getClientOriginalName();
            $fileModel->document = $fileName;
            $fileModel->licence_transfer_id = $transfer_id;
            $fileModel->doc_type = $request->document_type;
            $fileModel->num = $request->document_number;
            $fileModel->belongs_to = $request->belong_to;
            $fileModel->slug = sha1(now());
            $fileModel->save();

          //   $get_file_name = explode(".",$request->document->getClientOriginalName());
          //   $store_file = $request->document->store('transferDocuments','public'); 
          //  TransferDocument::create([
          //       "licence_transfer_id" => $transfer_id,
          //       "document_name" => $fileName,
          //       "document" => $store_file,
          //       "doc_type" => $request->document_type,
          //       "num" => $request->document_number,
          //       "belongs_to" => $request->belong_to,
          //       'slug' => sha1(now())
          //      ]);
       
      return back()->with('success','Document uploaded successfully.');
    
    }

    public function merge(Request $request){

         $exist =  LicenceTransfer::whereId($request->transfer_id)->whereNotNull('merged_document')->first(); 
        $merger = PDFMerger::init();           
           if (! is_null($exist)) {
             unlink(public_path('/storage/').$exist->merged_document);
             $exist->update(['merged_document' => null]);
           }
           
           if(is_null($request->latest_renewal)){
              $original_licence = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$request->original_licence['licence_id'])->first();
             if(!is_null($original_licence)){             
              
            File::copy(public_path('storage/licenceDocuments/'.$original_licence->document_file), public_path('storage/transferDocuments/'.$original_licence->document_file));
            $fileModel = new TransferDocument;
          
            $fileModel->document_name = $original_licence->document_name;
            $fileModel->document = $original_licence->document_file;
            $fileModel->licence_transfer_id = $request->transfer_id;
            $fileModel->doc_type = 'Original-Licence';
            $fileModel->num = 9;
            $fileModel->slug = sha1(now());
            $fileModel->save();
            
            }else{
              return back()->with('error','We could not find Original Licence document. Please make sure its uploaded.');
            }
          }
          //get proof of payment to merge
          $merge_proof_of_payment = TransferDocument::where('licence_transfer_id',$request->transfer_id)->where('doc_type','Payment To The Liquor Board')->first();
          $merge_proof_of_payment->update(['num' => 2]);
          
          $transfers =  TransferDocument::where('licence_transfer_id',$request->transfer_id)->whereNotNull('num')->orderBy('num','ASC')->get();
          $model =  LicenceTransfer::whereId($request->transfer_id)->first();
           foreach ($transfers as $transfer) {
             $merger->addPDF(public_path('/storage/transferDocuments/').$transfer->document, 'all');
           }
           $fileName = time().'.pdf';
           $merger->merge();
 
           $model->update(['merged_document' => $fileName]);
 
           $merger->save(public_path('/storage/'.$fileName));
           return back()->with('success','Document merged successfully.');
           
 
     }

    public function destroy($id){
        $model = TransferDocument::find($id);
            // unlink(storage_path('app/folder/'.$model->document_file));
            unlink(public_path('storage/transferDocuments/'.$model->document));
            $model->delete();
            return back()->with('success','Document removed successfully.');
      
    }

}
