<?php

namespace App\Http\Controllers\Wholesale;

use App\Models\Alteration;
use App\Models\AlterationDocument;
use App\Http\Controllers\Controller;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class WholesaleDocumentController extends Controller
{

    public function merge($id){
 
        try {
            $model =  Alteration::whereId($id)->first();
          $exist =  Alteration::whereId($id)->whereNotNull('merged_document')->first(['id','merged_document']); 
                  
          if (! is_null($exist)) {
            unlink(public_path('app/public/').$exist->merged_document);
            $exist->update(['merged_document' => null]);
          }
          $arr = ['NLA 14 Form','Proof of Payment','Annual Turnover Sheet','ID Documents','Police Clearance Certicates',
                'Contribution Certificate','Tax Clearance Certificates','BEE Certificate','CIPC Certificate','Zoning and LAA Certificate',
                'Latest NLA 33 Certificate'
            ];
                
         $alterations =  AlterationDocument::where('alteration_id',$model->id)
         
         ->whereIn('doc_type',$arr)
         ->orderBy('num','ASC')->get(['path']);
         
         $merger = PDFMerger::init(); 
        
          foreach ($alterations as $alteration) {
            $merger->addPDF(env('BLOB_FILE_PATH').$alteration->path, 'all');
          }
          $fileName = 'merged_alteration_'.time().'.pdf';
          $merger->merge();

          $model->update(['merged_document' => $fileName]);

          $merger->save(public_path('/app/public/'.$fileName));
          
          return back()->with('success','Document merged successfully.');
        } catch (\Throwable $th) {
          throw $th;
          return back()->with('error','Error merging document.');
        }
          

    }
   
}
