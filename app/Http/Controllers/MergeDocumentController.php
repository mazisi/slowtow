<?php

namespace App\Http\Controllers;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use App\Models\NominationDocument;
use setasign\Fpdi\Fpdi;

class MergeDocumentController extends Controller{

    public function merge($id){ 

       $nominations =  NominationDocument::where('nomination_id',$id)->get();

          $merger = PDFMerger::init();

          foreach ($nominations as $nom) {
            $merger->addPDF(public_path('/storage/').$nom->document, 'all');
          }
          $fileName = time().'.pdf';
          $merger->merge();

          $merger->save(public_path($fileName));
        
          return response()->download(public_path($fileName));

    }

}
