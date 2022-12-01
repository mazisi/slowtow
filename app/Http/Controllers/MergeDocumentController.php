<?php

namespace App\Http\Controllers;

use App\Models\MergedDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use App\Models\NominationDocument;

class MergeDocumentController extends Controller{

    public function merge($id){

       $nominations =  NominationDocument::where('nomination_id',$id)->get();

          $merger = PDFMerger::init();

          $exist = MergedDocument::where('nomination_id',$id)->first();
          if ($exist) {
            unlink(storage_path('/app/public/'.$exist->file_name));
            $exist->delete();
          }

          foreach ($nominations as $nom) {
            $merger->addPDF(env('BLOB_FILE_PATH').$nom->document, 'all');
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

}
